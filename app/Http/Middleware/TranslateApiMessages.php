<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class TranslateApiMessages
{
    /**
     * Keys that usually carry human-readable messages.
     */
    private const MESSAGE_KEYS = ['mess', 'error', 'message', 'success'];

    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            if (is_array($data)) {
                $response->setData($this->translatePayload($data));
            }
        }

        return $response;
    }

    /**
     * Recursively translate known message fields.
     */
    private function translatePayload(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->translatePayload($value);
                continue;
            }

            if (is_string($value) && in_array((string) $key, self::MESSAGE_KEYS, true)) {
                $payload[$key] = $this->translateMessage($value);
            }
        }

        return $payload;
    }

    /**
     * Replace common Russian backend messages with Spanish.
     */
    private function translateMessage(string $text): string
    {
        $map = [
            'Авторизуйтесь!' => 'Inicia sesion.',
            'Авторизуйтесь' => 'Inicia sesion.',
            'Ошибка №VK' => 'Error de VK.',
            'Ошибка в выборе ячеек' => 'Error al seleccionar celdas.',
            'Ошибка' => 'Error.',
            'Успешно' => 'Operacion exitosa.',
            'Недостаточно средств' => 'Fondos insuficientes.',
            'Подождите перед предыдущим действием!' => 'Espera antes de la accion anterior.',
            'Подождите 1 сек.' => 'Espera 1 segundo.',
            'Подождите 2 сек.' => 'Espera 2 segundos.',
            'Подождите 3 сек.' => 'Espera 3 segundos.',
            'Переключитесь на реальный баланс' => 'Cambia a saldo real.',
            'У вас есть активные игры' => 'Tienes juegos activos.',
            'Минимум 5' => 'Minimo 5.',
            'Минимальная сумма - 1р' => 'Monto minimo: 1.',
            'Максимальная сумма - 25000р' => 'Monto maximo: 25000.',
            'Минимальная сумма ставки 1' => 'Apuesta minima: 1.',
            'Минимальная ставка 1 монета' => 'Apuesta minima: 1 moneda.',
            'Максимальная ставка 100000 монет' => 'Apuesta maxima: 100000 monedas.',
            'Максимальная ставка 10000 монет' => 'Apuesta maxima: 10000 monedas.',
            'Максимум 1 ставка в раунде' => 'Maximo 1 apuesta por ronda.',
            'Максимум 3 ставки в раунде' => 'Maximo 3 apuestas por ronda.',
            'Промокод не найден или закончился' => 'Promocodigo no encontrado o caducado.',
            'Промокод будет доступен ' => 'El promocodigo estara disponible ',
            'Введите название промокода' => 'Ingresa el nombre del promocodigo.',
            'Вы уже активировали этот код' => 'Ya activaste este codigo.',
            'Введите сообщение' => 'Escribe un mensaje.',
            'Промокоды запрещены' => 'Los promocodigos estan prohibidos.',
            'Перевод не найден' => 'Transferencia no encontrada.',
            'Сумма меньше 1' => 'El monto minimo es 1.',
            'Перевод данному пользователю невозможен' => 'No es posible transferir a este usuario.',
            'Перевод себе же невозможен' => 'No puedes transferirte a ti mismo.',
            'У вас меньше 10 рефералов' => 'Tienes menos de 10 referidos.',
            'Теперь вы можете получить бонус' => 'Ahora puedes reclamar el bono.',
            'Вы уже получали бонус' => 'Ya recibiste este bono.',
            'Привяжите свой аккаунт TG' => 'Vincula tu cuenta de TG.',
            'Бонус успешно получен' => 'Bono recibido correctamente.',
            'Подпишитесь на нашу группу!' => 'Suscribete a nuestro grupo.',
            'Напишите "+" в личные сообщения группы' => 'Escribe "+" en los mensajes privados del grupo.',
            'Отыграйте еще ' => 'Debes apostar aun ',
            'Введите корректно сумму пополнения' => 'Ingresa correctamente el monto del deposito.',
            'Введите корректно сумму вывода' => 'Ingresa correctamente el monto del retiro.',
            'Введите корректно номер кошелька' => 'Ingresa correctamente el numero de billetera.',
            'Укажите систему вывода' => 'Selecciona un sistema de retiro.',
            'Минимальна сумма вывода ' => 'Monto minimo de retiro ',
            'Максимальная сумма вывода с бонуса ' => 'Monto maximo de retiro desde bono ',
            'У вас есть выводы в обработке' => 'Tienes retiros en proceso.',
            'Выш вывод отправляется. Отменить нельзя' => 'Tu retiro se esta enviando. No se puede cancelar.',
            'Введите сумму ставки корректно' => 'Ingresa correctamente el monto de apuesta.',
            'Введите процент корректно' => 'Ingresa correctamente el porcentaje.',
            'Введите корректное кол-во бомб' => 'Ingresa una cantidad de bombas valida.',
            'Минимальная сумма пополнения ' => 'Monto minimo de deposito ',
            'Вы получили ' => 'Recibiste ',
        ];

        return strtr($text, $map);
    }
}

