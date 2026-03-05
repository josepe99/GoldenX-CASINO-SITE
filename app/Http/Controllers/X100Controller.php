<?php

namespace App\Http\Controllers;

use App\Setting;
use App\X100;
use Auth;
use App\X100Anti;
use App\X100Round;
use App\X100WalletLedger;
use App\User;
use DB;
use App\ResultRandom;
use App\RandomKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Redis;

class X100Controller extends Controller
{
    private const BET_SECONDS = 15;
    private const SPIN_SECONDS = 15;
    private const ROUND_WAITING = 'WAITING';
    private const ROUND_BETTING = 'BETTING';
    private const ROUND_SPINNING = 'SPINNING';
    private const ROUND_FINISHED = 'FINISHED';
    private const SINGLE_SESSION_ACTIVE = 'ACTIVE';
    private const SINGLE_SESSION_LOST = 'LOST';
    private const SINGLE_SESSION_CASHED = 'CASHED';

    public function __construct()
    {
        parent::__construct();
        $this->redis = Redis::connection();
    }

    private function getActiveRound()
    {
        return X100Round::whereIn('status', [self::ROUND_BETTING, self::ROUND_SPINNING])
            ->orderBy('id', 'desc')
            ->first();
    }

    private function settingsHasColumn($column)
    {
        static $settingsColumns = null;
        if ($settingsColumns === null)
        {
            $settingsColumns = Schema::hasTable('settings') ? Schema::getColumnListing('settings') : [];
        }

        return in_array($column, $settingsColumns, true);
    }

    private function hasX100Table()
    {
        static $hasX100Table = null;
        if ($hasX100Table === null)
        {
            $hasX100Table = Schema::hasTable((new X100())->getTable());
        }

        return $hasX100Table;
    }

    private function hasX100HistoryTable()
    {
        static $hasX100HistoryTable = null;
        if ($hasX100HistoryTable === null)
        {
            $hasX100HistoryTable = Schema::hasTable('x100_history');
        }

        return $hasX100HistoryTable;
    }

    private function hasX100SingleSessionsTable()
    {
        static $hasX100SingleSessionsTable = null;
        if ($hasX100SingleSessionsTable === null)
        {
            $hasX100SingleSessionsTable = Schema::hasTable('x100_single_sessions');
        }

        return $hasX100SingleSessionsTable;
    }

    private function getActiveSingleSession($userId, $forUpdate = false)
    {
        if (!$this->hasX100SingleSessionsTable())
        {
            return null;
        }

        $query = DB::table('x100_single_sessions')
            ->where('user_id', (int)$userId)
            ->where('status', self::SINGLE_SESSION_ACTIVE)
            ->orderBy('id', 'desc');

        if ($forUpdate)
        {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    private function appendX100History($number, $coff, $random = '', $signature = '')
    {
        if (!$this->hasX100HistoryTable())
        {
            return;
        }

        DB::table('x100_history')->insert([
            'number' => (int)$number,
            'coff' => (int)$coff,
            'random' => $random ?: '',
            'signature' => $signature ?: '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function resolveSingleSpin($selectedCoff)
    {
        $doubleData = [
            0 => "20", 1 => "2", 2 => "3", 3 => "2", 4 => "15", 5 => "2", 6 => "3", 7 => "2", 8 => "20", 9 => "2",
            10 => "15", 11 => "2", 12 => "3", 13 => "2", 14 => "3", 15 => "2", 16 => "15", 17 => "2", 18 => "3", 19 => "10",
            20 => "3", 21 => "2", 22 => "10", 23 => "2", 24 => "3", 25 => "2", 26 => "100", 27 => "2", 28 => "3", 29 => "2",
            30 => "10", 31 => "2", 32 => "3", 33 => "2", 34 => "3", 35 => "2", 36 => "15", 37 => "2", 38 => "3", 39 => "2",
            40 => "3", 41 => "2", 42 => "20", 43 => "2", 44 => "3", 45 => "2", 46 => "10", 47 => "2", 48 => "3", 49 => "2",
            50 => "10", 51 => "2", 52 => "3", 53 => "2", 54 => "15", 55 => "2", 56 => "3", 57 => "2", 58 => "3", 59 => "2",
            60 => "10", 61 => "20", 62 => "3", 63 => "2", 64 => "3", 65 => "2", 66 => "15", 67 => "2", 68 => "10", 69 => "2",
            70 => "3", 71 => "2", 72 => "20", 73 => "2", 74 => "3", 75 => "2", 76 => "15", 77 => "2", 78 => "3", 79 => "2",
            80 => "10", 81 => "2", 82 => "3", 83 => "2", 84 => "3", 85 => "2", 86 => "10", 87 => "2", 88 => "3", 89 => "2",
            90 => "3", 91 => "2", 92 => "10", 93 => "2", 94 => "3", 95 => "2", 96 => "3", 97 => "2", 98 => "3", 99 => "2",
        ];

        $raw = $this->randNumber();
        if (isset($raw['result']['random']['data'][0]))
        {
            $number = (int)$raw['result']['random']['data'][0];
            $random = json_encode($raw['result']['random']);
            $signature = $raw['result']['signature'];
        }
        else
        {
            $number = random_int(0, 99);
            $random = '';
            $signature = '';
        }

        $resultCoff = (int)$doubleData[$number];
        $won = (int)$selectedCoff === $resultCoff;

        return [
            'number' => $number,
            'coff' => $resultCoff,
            'won' => $won,
            'random' => $random,
            'signature' => $signature,
            'target_angle' => $this->resolveTargetAngleByNumber($number),
        ];
    }

    private function resolveTargetAngleByNumber($number)
    {
        $normalized = ((int)$number % 100 + 100) % 100;
        return (float)((360 / 100) * $normalized);
    }

    private function saveSettingIfDirty($setting)
    {
        if ($setting && $setting->isDirty())
        {
            $setting->save();
        }
    }

    private function markRoundAsFinished(X100Round $round)
    {
        $round->status = self::ROUND_FINISHED;
        if (!$round->settled_at)
        {
            $round->settled_at = now();
        }
        $round->save();
    }

    private function getLatestRound()
    {
        return X100Round::orderBy('id', 'desc')->first();
    }

    private function buildRoundPayload($round)
    {
        if (!$round)
        {
            return null;
        }

        $now = now();
        $secondsLeft = 0;
        if ($round->status === self::ROUND_BETTING && $round->betting_ends_at)
        {
            $secondsLeft = max(0, $round->betting_ends_at->timestamp - $now->timestamp);
        }
        elseif ($round->status === self::ROUND_SPINNING && $round->spinning_ends_at)
        {
            $secondsLeft = max(0, $round->spinning_ends_at->timestamp - $now->timestamp);
        }

        return [
            'id' => $round->id,
            'status' => $round->status,
            'server_time' => $now->timestamp,
            'betting_ends_at' => optional($round->betting_ends_at)->timestamp,
            'spinning_ends_at' => optional($round->spinning_ends_at)->timestamp,
            'result_coff' => $round->result_coff ? (int)$round->result_coff : null,
            'seconds_left' => $secondsLeft,
        ];
    }

    // Fallback lifecycle guard: if socket worker is down, polling /x100/get still progresses rounds.
    private function syncRoundLifecycle()
    {
        $round = $this->getActiveRound();
        if (!$round)
        {
            return;
        }

        $now = now();
        if ($round->status === self::ROUND_BETTING && $round->betting_ends_at && $now->gte($round->betting_ends_at))
        {
            $this->generateNumber();
            return;
        }

        if ($round->status === self::ROUND_SPINNING && $round->spinning_ends_at && $now->gte($round->spinning_ends_at))
        {
            if (!$this->hasX100Table())
            {
                $this->markRoundAsFinished($round);
                return;
            }
            $this->winWheel(request());
            return;
        }
    }

    public function go(Request $request)
    {
        $color = (int)$request->coff;
        $arr = [2, 3, 10, 15, 20, 100];
        if (!in_array($color, $arr, true))
        {
            return response(['success' => false, 'mess' => 'Ошибка']);
        }

        $round = $this->getActiveRound();
        if (!$round || $round->status !== self::ROUND_BETTING)
        {
            return response(['success' => false, 'mess' => 'Раунд не в фазе ставок']);
        }

        $round->forced_coff = $color;
        $round->save();

        // Keep legacy flag for existing anti/legacy logic consumers.
        $set = Setting::first();
        if ($set && $this->settingsHasColumn('win_x100'))
        {
            $set->win_x100 = $color;
            $this->saveSettingIfDirty($set);
        }

        return response(['success' => true, 'mess' => 'Подкрутка на x' . $color]);
    }

    public function start()
    {
        if (!$this->hasX100Table())
        {
            return response(['success' => false, 'mess' => 'X100 no está disponible. Falta ejecutar migraciones.']);
        }

        $round = DB::transaction(function () {
            $activeRound = X100Round::whereIn('status', [self::ROUND_BETTING, self::ROUND_SPINNING])
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            if ($activeRound)
            {
                $now = now();
                $bettingExpired = $activeRound->status === self::ROUND_BETTING &&
                    (!$activeRound->betting_ends_at || $now->gte($activeRound->betting_ends_at));
                $spinningExpired = $activeRound->status === self::ROUND_SPINNING &&
                    (!$activeRound->spinning_ends_at || $now->gte($activeRound->spinning_ends_at));

                if ($bettingExpired || $spinningExpired)
                {
                    $this->markRoundAsFinished($activeRound);
                    $activeRound = null;
                }
            }

            if ($activeRound)
            {
                return null;
            }

            $round = X100Round::create([
                'status' => self::ROUND_BETTING,
                'started_at' => now(),
                'betting_ends_at' => now()->copy()->addSeconds(self::BET_SECONDS),
            ]);

            return $round;
        });

        if (!$round)
        {
            return response(['success' => false, 'mess' => 'La ronda ya se ha lanzado']);
        }

        $payload = [
            'start' => true,
            'time' => time(),
            'round_id' => $round->id,
            'bet_seconds' => self::BET_SECONDS,
            'spin_seconds' => self::SPIN_SECONDS,
            'betting_ends_at' => optional($round->betting_ends_at)->timestamp,
        ];
        $this->redis->publish('x100StartRound', json_encode($payload));

        return response([
            'success' => true,
            'mess' => 'Ronda X100 iniciada',
            'round' => $this->buildRoundPayload($round),
        ]);
    }

    public function bonusGo(Request $r){
        $user_id = $r->user_id;
        $avatar = $r->avatar;

        $round = $this->getActiveRound();
        if (!$round || $round->status !== self::ROUND_SPINNING)
        {
            $set = Setting::first();
            if ($set)
            {
                if ($this->settingsHasColumn('X100BonusUser_ID'))
                {
                    $set->X100BonusUser_ID = $user_id;
                }
                if ($this->settingsHasColumn('X100BonusAvatar'))
                {
                    $set->X100BonusAvatar = $avatar;
                }
                $this->saveSettingIfDirty($set);
            }
            return response(['success' => true, 'mess' => 'Бонуска успешно подкручена']);
        }
        else
        {
            return response(['success' => false, 'mess' => 'Идет раунд, нельзя крутить']);
        }
    }

    public function getKey()
    {
        $MAX_RANDOM_KEY_ID = 13;
        $MIN_RANDOM_KEY_ID = 11;

        if (!Schema::hasColumn('settings', 'random_key_id'))
        {
            return null;
        }

        $setting = Setting::first();
        if (!$setting)
        {
            return null;
        }

        $randomKeyTable = (new RandomKey())->getTable();
        if (!Schema::hasTable($randomKeyTable))
        {
            return null;
        }

        $l_key_id = $setting->random_key_id;
        $key_id = $l_key_id + 1;

        if ($key_id > $MAX_RANDOM_KEY_ID)
        {
            $key_id = $MIN_RANDOM_KEY_ID;
        }

        $setting->random_key_id = $key_id;
        $setting->save();

        $random_key = RandomKey::where('id', $key_id)->first();
        if (!$random_key)
        {
            return null;
        }

        $key = $random_key->name_key;
        if (!$key)
        {
            return null;
        }

        $random_key->games += 1;
        $random_key->save();

        return $key;

    }

    public function randNumber()
    {
        $key = self::getKey();
        if (!$key)
        {
            return [];
        }
        
        $p = array(
            'apiKey' => "$key",
            'n' => 1,
            'min' => 0,
            'max' => 99,
            'replacement' => false,
        );
        $params = array(
            'jsonrpc' => "2.0",
            'method' => "generateSignedIntegers",
            'id' => 1,
            'params' => $p
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.random.org/json-rpc/1/invoke');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        $out = curl_exec($ch);
        if ($out === false)
        {
            curl_close($ch);
            return [];
        }
        curl_close($ch);
        //return dd($out);
        return json_decode($out, true);
    }

    public function searchNumber($data, $massiv, $resultat){
        foreach ($massiv as $m) {
            if($data[$m] == $resultat){
                return $m;
            }
            unset($massiv[0]);
            $massiv = array_values($massiv);
        }
    }

    public function generateNumber()
    {

        $doubleData = array(
         0 => "20",
         1 => "2",
         2 => "3",
         3 => "2",
         4 => "15",
         5 => "2",
         6 => "3",
         7 => "2",
         8 => "20",
         9 => "2",
         10 => "15",
         11 => "2",
         12 => "3",
         13 => "2",
         14 => "3",
         15 => "2",
         16 => "15",
         17 => "2",
         18 => "3",
         19 => "10",
         20 => "3",
         21 => "2",
         22 => "10",
         23 => "2",
         24 => "3",
         25 => "2",

         26 => "100",
         27 => "2",
         28 => "3",
         29 => "2",
         30 => "10",
         31 => "2",
         32 => "3",
         33 => "2",
         34 => "3",
         35 => "2",
         36 => "15",
         37 => "2",
         38 => "3",
         39 => "2",
         40 => "3",
         41 => "2",
         42 => "20",
         43 => "2",
         44 => "3",
         45 => "2",
         46 => "10",
         47 => "2",
         48 => "3",
         49 => "2",
         50 => "10",

         51 => "2",
         52 => "3",
         53 => "2",
         54 => "15",
         55 => "2",
         56 => "3",
         57 => "2",
         58 => "3",
         59 => "2",
         60 => "10",
         61 => "20",
         62 => "3",
         63 => "2",
         64 => "3",
         65 => "2",
         66 => "15",
         67 => "2",
         68 => "10",
         69 => "2",
         70 => "3",
         71 => "2",
         72 => "20",
         73 => "2",
         74 => "3",
         75 => "2",

         76 => "15",
         77 => "2",
         78 => "3",
         79 => "2",
         80 => "10",
         81 => "2",
         82 => "3",
         83 => "2",
         84 => "3",
         85 => "2",
         86 => "10",
         87 => "2",
         88 => "3",
         89 => "2",
         90 => "3",
         91 => "2",
         92 => "10",
         93 => "2",
         94 => "3",
         95 => "2",
         96 => "3",
         97 => "2",
         98 => "3",
         99 => "2"
     );

        $setting = Setting::first();
        $round = $this->getActiveRound();

        $result = self::randNumber();
        if (!isset($result['result']['random']['data'][0]))
        {
            $rand = rand(0, 99);
            $random = '';
            $signature = '';
        }
        else
        {
            $rand = (int)$result['result']['random']['data'][0];
            $random = json_encode($result['result']['random']);
            $signature = $result['result']['signature'];
        }
        $resultat = $doubleData[$rand];

        // $rand = rand(0, 99);
        // $random = '';
        // $signature = '';
        // $resultat = $doubleData[$rand];

        $wheel_win = $this->settingsHasColumn('win_x100') ? $setting->win_x100 : 'false';
        if ($round && $round->forced_coff)
        {
            $wheel_win = (string)$round->forced_coff;
        }
        $coeff_bonus  = $setting->coeff_bonus;
        $auto_wheel = $setting->auto_x100;
        $youtube = $setting->youtube;

        if($wheel_win != 'false'){
            ////// подкрутка
            $resultat = $wheel_win;

            $massiv = range(0, 99);
            shuffle($massiv);
            $rand = self::searchNumber($doubleData, $massiv, $resultat);      
        }


        if ($youtube == 0 && $wheel_win == 'false')
        {
            $antiTable = (new X100Anti())->getTable();
            if (Schema::hasTable($antiTable))
            {
                $selectedAnti = X100Anti::where('coeff', $resultat)->first();
                if ($selectedAnti && (float)$selectedAnti->win > 500)
                {
                    $wheelAntiSuccess = X100Anti::where('win', '<', 500)->inRandomOrder()->first();
                    if (!$wheelAntiSuccess)
                    {
                        $wheelAntiSuccess = X100Anti::orderBy('win', 'asc')->first();
                    }

                    if ($wheelAntiSuccess)
                    {
                        $resultat = (string)$wheelAntiSuccess->coeff;
                    }
                }
            }

            $massiv = range(0, 99);
            shuffle($massiv);
            $rand = self::searchNumber($doubleData, $massiv, $resultat);
        }
        
        if ($this->settingsHasColumn('win_x100'))
        {
            $setting->win_x100 = 'false';
        }
        $this->saveSettingIfDirty($setting);

        if ($round && $round->status === self::ROUND_BETTING)
        {
            $round->status = self::ROUND_SPINNING;
            $round->result_coff = (int)$resultat;
            $round->spinning_ends_at = now()->copy()->addSeconds(self::SPIN_SECONDS);
            $round->save();
        }


        return array(
            'number' => $rand,
            'signature' => $signature,
            'random' => $random,
            'coff' => (int)$resultat,
            'target_angle' => $this->resolveTargetAngleByNumber($rand),
            'spin_seconds' => self::SPIN_SECONDS,
            'round_id' => $round ? $round->id : null,
        );
    }

      public function winWheel(Request $r)
    {
        DB::transaction(function () {
            $round = X100Round::where('status', self::ROUND_SPINNING)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            if (!$round)
            {
                $round = X100Round::lockForUpdate()->orderBy('id', 'desc')->first();
            }

            if (!$round || $round->settled_at)
            {
                return true;
            }

            $setting = Setting::lockForUpdate()->first();
            $fallbackWinNumber = $this->settingsHasColumn('x100WinNumber') ? (int)$setting->x100WinNumber : 0;
            $wheelWinNumber = (int)($round->result_coff ?: $fallbackWinNumber);
            $X100BonusUser_ID = $this->settingsHasColumn('X100BonusUser_ID') ? (int)$setting->X100BonusUser_ID : 0;

            if (!$this->hasX100Table())
            {
                if ($this->settingsHasColumn('x100WinNumber'))
                {
                    $setting->x100WinNumber = $wheelWinNumber;
                }
                if ($this->settingsHasColumn('win_x100'))
                {
                    $setting->win_x100 = 'false';
                }
                if (Schema::hasColumn('settings', 'status_x100'))
                {
                    $setting->status_x100 = 0;
                }
                $this->saveSettingIfDirty($setting);

                $round->status = self::ROUND_FINISHED;
                $round->settled_at = now();
                $round->save();
                return true;
            }

            $roundBets = X100::where('round_id', $round->id)->lockForUpdate()->get();
            foreach ($roundBets as $betRow)
            {
                if ((int)$betRow->settled === 1)
                {
                    continue;
                }

                $user = User::where('id', $betRow->user_id)->lockForUpdate()->first();
                if (!$user)
                {
                    $betRow->settled = 1;
                    $betRow->save();
                    continue;
                }

                $payout = 0;
                if ((int)$betRow->coff === $wheelWinNumber)
                {
                    $multiplier = $X100BonusUser_ID === (int)$user->id ? 4 : 1;
                    $payout = round($betRow->bet * $wheelWinNumber * $multiplier, 2);

                    $userBalance = $user->type_balance == 0 ? $user->balance : $user->demo_balance;
                    $newBalance = $userBalance + $payout;

                    $user->win_games += 1;
                    $user->sum_win += $payout;
                    if ($user->max_win < $payout)
                    {
                        $user->max_win = $payout;
                    }

                    if ($user->type_balance == 0)
                    {
                        $user->balance = $newBalance;
                        if ($this->settingsHasColumn('wheel_bank'))
                        {
                            $setting->wheel_bank -= $payout;
                        }
                    }
                    else
                    {
                        $user->demo_balance = $newBalance;
                    }
                    $user->save();

                    X100WalletLedger::create([
                        'round_id' => $round->id,
                        'x100_id' => $betRow->id,
                        'user_id' => $user->id,
                        'coff' => (int)$betRow->coff,
                        'entry_type' => 'CREDIT',
                        'amount' => $payout,
                        'balance_before' => $userBalance,
                        'balance_after' => $newBalance,
                        'meta' => json_encode(['reason' => 'x100_win']),
                    ]);

                    $callback = ['user_id' => $user->id, 'lastbalance' => $userBalance, 'newbalance' => $newBalance];
                    $this->redis->publish('updateBalance', json_encode($callback));
                }
                else
                {
                    $user->lose_games += 1;
                    $user->save();
                }

                $betRow->payout = $payout;
                $betRow->settled = 1;
                $betRow->save();
            }

            if ($this->settingsHasColumn('x100WinNumber'))
            {
                $setting->x100WinNumber = $wheelWinNumber;
            }
            if ($this->settingsHasColumn('win_x100'))
            {
                $setting->win_x100 = 'false';
            }
            $this->saveSettingIfDirty($setting);

            $round->status = self::ROUND_FINISHED;
            $round->settled_at = now();
            $round->save();

            if (Schema::hasColumn('settings', 'status_x100'))
            {
                $setting->status_x100 = 0;
                $this->saveSettingIfDirty($setting);
            }
        });

        return true;
    }

    public function get()
    {
        $wheel = collect();
        $arr = collect([2, 3, 10, 15, 20, 100])->map(function ($coff) {
            return ['coff' => $coff, 'players' => 0, 'sum' => 0];
        })->values()->all();
        $history = collect();
        if ($this->hasX100HistoryTable())
        {
            $history = DB::table('x100_history')->select(['id', 'coff', 'number', 'random', 'signature'])
            ->orderBy('id', 'desc')
            ->take('50')
            ->get();
        }

        $session = null;
        if (Auth::check())
        {
            $rawSession = $this->getActiveSingleSession(Auth::id(), false);
            if ($rawSession)
            {
                $session = [
                    'id' => (int)$rawSession->id,
                    'coff' => (int)$rawSession->coff,
                    'current_amount' => (float)$rawSession->current_amount,
                    'rounds_played' => (int)$rawSession->rounds_played,
                    'status' => $rawSession->status,
                ];
            }
        }

        return response([
            'success' => $wheel,
            'round' => null,
            'info' => $arr,
            'history' => $history,
            'single_mode' => true,
            'session' => $session,
        ]);
    }

    public function bet(Request $r)
    {
        if (!Auth::check())
        {
            return response(['error' => 'Debes iniciar sesión.']);
        }

        $coff = (int)$r->coff;
        $bet = round($r->bet, 2);

        $user = Auth::user();
        if($user->ban == 1){
            return response(['error' => 'Se produjo un error desconocido']);
        }
        if($user->admin != 1){
            // return response(['error' => 'Тех работы до 20:00 по МСК']);
        }

        if (\Cache::has('action.user.' . $user->id)) return response(['error' => 'Espere 1 segundo.']);
        \Cache::put('action.user.' . $user->id, '', 1);

        $mycoff = [2, 3, 10, 15, 20, 100];

        if (!in_array($coff, $mycoff))
        {
            return response(['error' => 'Произошла ошибка']);
        }

        if ($bet < 1)
        {
            return response(['error' => 'Минимальная ставка 1 монета']);
        }

        if ($bet > 100000)
        {
            return response(['error' => 'Максимальная ставка 100000 монет']);
        }

        $spin = $this->resolveSingleSpin($coff);
        $payout = $spin['won'] ? round($bet * $coff, 2) : 0;

        $result = DB::transaction(function () use ($user, $coff, $bet, $spin, $payout) {
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

            $balanceBefore = $lockedUser->type_balance == 0 ? (float)$lockedUser->balance : (float)$lockedUser->demo_balance;
            if ($bet > $balanceBefore)
            {
                return ['error' => 'Fondos insuficientes'];
            }

            $balanceAfterBet = round($balanceBefore - $bet, 2);
            $balanceAfter = $balanceAfterBet;
            if ($spin['won'])
            {
                $balanceAfter = round($balanceAfterBet + $payout, 2);
            }

            if ($lockedUser->type_balance == 0)
            {
                $lockedUser->balance = $balanceAfter;
            }
            else
            {
                $lockedUser->demo_balance = $balanceAfter;
            }
            $lockedUser->sum_bet += $bet;
            $lockedUser->sum_to_withdraw -= $bet;

            if ($spin['won'])
            {
                $lockedUser->win_games += 1;
                $lockedUser->sum_win += $payout;
                if ($lockedUser->max_win < $payout)
                {
                    $lockedUser->max_win = $payout;
                }
            }
            else
            {
                $lockedUser->lose_games += 1;
            }
            $lockedUser->save();

            if (Schema::hasTable('x100_wallet_ledger'))
            {
                X100WalletLedger::create([
                    'round_id' => null,
                    'x100_id' => null,
                    'user_id' => $lockedUser->id,
                    'coff' => (int)$coff,
                    'entry_type' => 'DEBIT',
                    'amount' => $bet,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfterBet,
                    'meta' => json_encode(['reason' => 'x100_single_bet']),
                ]);

                if ($spin['won'] && $payout > 0)
                {
                    X100WalletLedger::create([
                        'round_id' => null,
                        'x100_id' => null,
                        'user_id' => $lockedUser->id,
                        'coff' => (int)$coff,
                        'entry_type' => 'CREDIT',
                        'amount' => $payout,
                        'balance_before' => $balanceAfterBet,
                        'balance_after' => $balanceAfter,
                        'meta' => json_encode(['reason' => 'x100_single_win']),
                    ]);
                }
            }

            $callback = ['user_id' => $lockedUser->id, 'lastbalance' => $balanceBefore, 'newbalance' => $balanceAfter];
            $this->redis->publish('updateBalance', json_encode($callback));

            return [
                'success' => true,
                'lastbalance' => $balanceBefore,
                'newbalance' => $balanceAfter,
            ];
        });

        if (isset($result['error']))
        {
            return response(['error' => $result['error']]);
        }

        $this->appendX100History($spin['number'], $spin['coff'], $spin['random'], $spin['signature']);

        return response([
            'success' => true,
            'mess' => $spin['won'] ? 'Ganaste la ronda.' : 'Perdiste la ronda.',
            'lastbalance' => $result['lastbalance'],
            'newbalance' => $result['newbalance'],
            'result' => [
                'number' => (int)$spin['number'],
                'coff' => (int)$spin['coff'],
                'won' => (bool)$spin['won'],
                'selected_coff' => (int)$coff,
                'target_angle' => (float)$spin['target_angle'],
            ],
            'pending_amount' => 0,
            'can_continue' => false,
            'session' => null,
        ]);
    }

    public function continueSingle(Request $request)
    {
        if (!Auth::check())
        {
            return response(['error' => 'Debes iniciar sesión.']);
        }
        if (!$this->hasX100SingleSessionsTable())
        {
            return response(['error' => 'X100 no está disponible todavía. Falta ejecutar migraciones.']);
        }

        $user = Auth::user();
        $requestedCoff = (int)$request->input('coff', 0);
        $allowedCoffs = [2, 3, 10, 15, 20, 100];

        $result = DB::transaction(function () use ($user, $requestedCoff, $allowedCoffs) {
            $session = $this->getActiveSingleSession($user->id, true);
            if (!$session)
            {
                return ['error' => 'No tienes una ronda activa para continuar.'];
            }

            $coff = $requestedCoff > 0 ? $requestedCoff : (int)$session->coff;
            if (!in_array($coff, $allowedCoffs, true))
            {
                return ['error' => 'Coeficiente inválido.'];
            }

            $stake = round((float)$session->current_amount, 2);
            if ($stake <= 0)
            {
                return ['error' => 'La sesión activa no tiene saldo.'];
            }

            $spin = $this->resolveSingleSpin($coff);
            $newAmount = $spin['won'] ? round($stake * $coff, 2) : 0;

            DB::table('x100_single_sessions')
                ->where('id', $session->id)
                ->update([
                    'coff' => $coff,
                    'current_amount' => $newAmount,
                    'status' => $spin['won'] ? self::SINGLE_SESSION_ACTIVE : self::SINGLE_SESSION_LOST,
                    'rounds_played' => (int)$session->rounds_played + 1,
                    'last_result_coff' => (int)$spin['coff'],
                    'last_number' => (int)$spin['number'],
                    'last_random' => $spin['random'],
                    'last_signature' => $spin['signature'],
                    'ended_at' => $spin['won'] ? null : now(),
                    'updated_at' => now(),
                ]);

            if (!$spin['won'])
            {
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
                $lockedUser->lose_games += 1;
                $lockedUser->save();
            }

            $this->appendX100History($spin['number'], $spin['coff'], $spin['random'], $spin['signature']);

            return [
                'success' => true,
                'won' => $spin['won'],
                'coff' => (int)$spin['coff'],
                'number' => (int)$spin['number'],
                'selected_coff' => (int)$coff,
                'target_angle' => (float)$spin['target_angle'],
                'pending_amount' => $newAmount,
            ];
        });

        if (isset($result['error']))
        {
            return response(['error' => $result['error']]);
        }

        return response([
            'success' => true,
            'mess' => $result['won'] ? 'Ronda continuada con éxito.' : 'Perdiste la ronda.',
            'result' => [
                'number' => $result['number'],
                'coff' => $result['coff'],
                'won' => $result['won'],
                'selected_coff' => $result['selected_coff'],
                'target_angle' => $result['target_angle'],
            ],
            'pending_amount' => $result['pending_amount'],
            'can_continue' => $result['won'],
            'session' => $result['won'] ? $this->getActiveSingleSession($user->id, false) : null,
        ]);
    }

    public function cashoutSingle()
    {
        if (!Auth::check())
        {
            return response(['error' => 'Debes iniciar sesión.']);
        }
        if (!$this->hasX100SingleSessionsTable())
        {
            return response(['error' => 'X100 no está disponible todavía. Falta ejecutar migraciones.']);
        }

        $user = Auth::user();
        $result = DB::transaction(function () use ($user) {
            $session = $this->getActiveSingleSession($user->id, true);
            if (!$session)
            {
                return ['error' => 'No tienes una ronda activa para terminar.'];
            }

            $amount = round((float)$session->current_amount, 2);
            if ($amount <= 0)
            {
                return ['error' => 'No hay saldo acumulado para acreditar.'];
            }

            $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
            $balanceBefore = $lockedUser->type_balance == 0 ? (float)$lockedUser->balance : (float)$lockedUser->demo_balance;
            $balanceAfter = round($balanceBefore + $amount, 2);

            if ($lockedUser->type_balance == 0)
            {
                $lockedUser->balance = $balanceAfter;
            }
            else
            {
                $lockedUser->demo_balance = $balanceAfter;
            }

            $lockedUser->win_games += 1;
            $lockedUser->sum_win += $amount;
            if ($lockedUser->max_win < $amount)
            {
                $lockedUser->max_win = $amount;
            }
            $lockedUser->save();

            DB::table('x100_single_sessions')->where('id', $session->id)->update([
                'status' => self::SINGLE_SESSION_CASHED,
                'cashed_amount' => $amount,
                'ended_at' => now(),
                'updated_at' => now(),
            ]);

            if (Schema::hasTable('x100_wallet_ledger'))
            {
                X100WalletLedger::create([
                    'round_id' => null,
                    'x100_id' => null,
                    'user_id' => $lockedUser->id,
                    'coff' => (int)$session->coff,
                    'entry_type' => 'CREDIT',
                    'amount' => $amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'meta' => json_encode(['reason' => 'x100_single_cashout']),
                ]);
            }

            $callback = ['user_id' => $lockedUser->id, 'lastbalance' => $balanceBefore, 'newbalance' => $balanceAfter];
            $this->redis->publish('updateBalance', json_encode($callback));

            return [
                'success' => true,
                'amount' => $amount,
                'lastbalance' => $balanceBefore,
                'newbalance' => $balanceAfter,
            ];
        });

        if (isset($result['error']))
        {
            return response(['error' => $result['error']]);
        }

        return response([
            'success' => true,
            'mess' => 'Monto acreditado a la wallet.',
            'amount' => $result['amount'],
            'lastbalance' => $result['lastbalance'],
            'newbalance' => $result['newbalance'],
        ]);

    }
}
