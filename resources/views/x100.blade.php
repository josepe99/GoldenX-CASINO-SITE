@php
$setting = \App\Setting::first();
@endphp
<style>
    .x100 .x30__bet-heading.x100-selected {
        box-shadow: 0 0 0 2px #ffffff inset, 0 0 0 3px rgba(47, 179, 68, 0.9);
        transform: translateY(-1px);
    }
</style>
<div style="margin-top: 35px;" class="x30 x100">
    <div class="x30__wheel d-flex justify-center flex-column align-center">
        <div class="x30__wheels d-flex justify-center align-end">
            <div class="x30__wheel-center d-flex justify-center align-start">
                <div class="wheel__x100-bonus-x bonusBlock" style="display: none;">
                    <div class="wheel__x100-bonus-bg"></div>
                    <div class="wheel__x100-bonus d-flex justify-end align-center">
                        <div class="wheel__x100-bonus-content">
                            <div class="wheel__x100-bonus-slider">
                                <div class="wheel__x100-bonus-cursor"></div>
                                <div class="wheel__x100-bonus-scroll d-flex align-center">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="x30__timer TimerBlock d-flex flex-column justify-center align-center" >
                    <b id="x100__text">Esperando inicio</b>
                    <div id="x100_selected_coff" style="display: none; margin-top: 6px; font-weight: 700; color: #ffffff;"></div>
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <button
                        type="button"
                        id="x100_start_button"
                        onclick="startRoundX100()"
                        style="margin-top: 8px; border: 0; border-radius: 10px; background: #2FB344; color: #fff; font-weight: 700; padding: 7px 16px; cursor: pointer;"
                    >
                        Empezar
                    </button>
                    <span id="x100__timer" style="display: none;">15</span>
                    @else
                    <span id="x100__timer">15</span>
                    @endif
                    @else
                    <span id="x100__timer">15</span>
                    @endauth
                </div>
            </div>
            <div class="x30__wheel-image">
                <img src="images/games/x100/wheel.svg" id="x100__wheel" style="transition: transform 15s ease 0s; transform: rotate(-1.8deg);">
            </div>
            <div class="x30__wheel-border"></div>
        </div>
        <div class="x30__cursor"></div>
    </div> 
    <div class="wrapper">
        <div class="x30__top">
            <a href="#" rel="popup" data-popup="popup--x100" class="help d-flex align-center">
                <svg class="icon"><use xlink:href="images/symbols.svg#faq"></use></svg>
                <span>¿Cómo jugar?</span>
            </a>
            <div class="x30__rocket d-flex align-center" id="x100__status">
                @if($setting->theme == 0)
                    <img class="x30__rocket-img" src="images/rocket.png">
                @else
                    <img class="x30__rocket-img" src="images/snow/ded.png">
                @endif
                <div class="x30__rocket-coins"></div>
            </div>
        </div>
        <div class="x30__bottom">
            <div class="x30__bet d-flex align-center justify-space-between">
                <div class="x30__history">
                    <div class="bx-input__input d-flex align-center justify-space-between pd10-20">
                        <label class="d-flex align-center">Historial:</label>
                        <div class="x100__history-items">
                            <div class="x100__history-scroll d-flex align-center">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="x30__bet-game">
                    <div class="bx-input__input d-flex align-center justify-space-between flex-wrap pd10-20">
                        <div class="d-flex align-center justify-space-between">
                            
                                <input style="text-align: left;" placeholder="0.00" type="text" value="1.00" id="sumBetX100">
                                <svg class="icon money"><use xlink:href="images/symbols.svg#coins"></use></svg>
                            
                        </div>
                        <div class="x30__bet-placed d-flex align-center justify-space-between">
                            <a onclick="$('#sumBetX100').val(1)">Min</a>
                            <a onclick="$('#sumBetX100').val(Number($('#balance').attr('balance')))">Max</a>
                            <a onclick="$('#sumBetX100').val(Number($('#sumBetX100').val()) + 10)">+10</a>
                            <a onclick="$('#sumBetX100').val(Number($('#sumBetX100').val()) + 100)">+100</a>
                            <a onclick="$('#sumBetX100').val((Number($('#sumBetX100').val()) * 2).toFixed(2))">x2</a>
                            <a onclick="$('#sumBetX100').val(Math.max(($('#sumBetX100').val()/2), 1).toFixed(2));">1/2</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="x30__bets">
                <div class="x30__bet">
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <div class="plusBlock" onclick="goX100('2')">Go</div>
                    @endif
                    @endauth
                    <div onclick="disable(this);betX100('2')" class="x30__bet-heading is-ripples flare x2 d-flex align-center justify-space-between">
                        <span>X2</span>
                    </div>
                    <div class="x30__bet-info d-flex align-center justify-space-between">
                        <span class="d-flex align-center" style="color: #9EABCD;">
                            <svg class="icon small" style="margin-right: 8px;"><use xlink:href="images/symbols.svg#users"></use></svg>
                            <span data-playersX100=2></span>
                       </span>
                        <span class="d-flex align-center" style="font-weight: 600;">
                            <span data-sumBetsX100=2></span>
                            <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>
                        </span>
                    </div>
                    <div class="x100__bet-users x2">

                    </div>
                </div>
                <div class="x30__bet">
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <div class="plusBlock" onclick="goX100('3')">Go</div>
                    @endif
                    @endauth
                    <div onclick="disable(this);betX100('3')" class="x30__bet-heading is-ripples flare x3 d-flex align-center justify-space-between">
                        <span>X3</span>
                    </div>
                    <div class="x30__bet-info d-flex align-center justify-space-between">
                        <span class="d-flex align-center" style="color: #9EABCD;">
                            <svg class="icon small" style="margin-right: 8px;"><use xlink:href="images/symbols.svg#users"></use></svg>
                            <span data-playersX100=3></span>
                        </span>
                        <span class="d-flex align-center" style="font-weight: 600;">
                            <span data-sumBetsX100=3></span>
                            <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>
                        </span>
                    </div>
                    <div class="x100__bet-users x3">

                    </div>
                </div>
                <div class="x30__bet">
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <div class="plusBlock" onclick="goX100('10')">Go</div>
                    @endif
                    @endauth
                    <div onclick="disable(this);betX100('10')" class="x30__bet-heading is-ripples flare x10 d-flex align-center justify-space-between">
                        <span>X10</span>
                    </div>
                    <div class="x30__bet-info d-flex align-center justify-space-between">
                        <span class="d-flex align-center" style="color: #9EABCD;">
                            <svg class="icon small" style="margin-right: 8px;"><use xlink:href="images/symbols.svg#users"></use></svg>
                            <span data-playersX100=10></span>
                        </span>
                        <span class="d-flex align-center" style="font-weight: 600;">
                            <span data-sumBetsX100=10></span>
                            <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>
                        </span>
                    </div>
                    <div class="x100__bet-users x10">

                    </div>
                </div>
                <div class="x30__bet">
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <div class="plusBlock" onclick="goX100('15')">Go</div>
                    @endif
                    @endauth
                    <div onclick="disable(this);betX100('15')" class="x30__bet-heading is-ripples flare x15 d-flex align-center justify-space-between">
                        <span>X15</span>
                    </div>
                    <div class="x30__bet-info d-flex align-center justify-space-between">
                        <span class="d-flex align-center" style="color: #9EABCD;">
                            <svg class="icon small" style="margin-right: 8px;"><use xlink:href="images/symbols.svg#users"></use></svg>
                            <span data-playersX100=15></span>
                        </span>
                        <span class="d-flex align-center" style="font-weight: 600;">
                            <span data-sumBetsX100=15></span>
                            <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>
                        </span>
                    </div>
                    <div class="x100__bet-users x15">

                    </div>
                </div>
                <div class="x30__bet">
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <div class="plusBlock" onclick="goX100('20')">Go</div>
                    @endif
                    @endauth
                    <div onclick="disable(this);betX100('20')" class="x30__bet-heading is-ripples flare x20 d-flex align-center justify-space-between">
                        <span>X20</span>
                    </div>
                    <div class="x30__bet-info d-flex align-center justify-space-between">
                        <span class="d-flex align-center" style="color: #9EABCD;">
                            <svg class="icon small" style="margin-right: 8px;"><use xlink:href="images/symbols.svg#users"></use></svg>
                            <span data-playersX100=20></span>
                        </span>
                        <span class="d-flex align-center" style="font-weight: 600;">
                            <span data-sumBetsX100=20></span>
                            <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>
                        </span>
                    </div>
                    <div class="x100__bet-users x20">

                    </div>
                </div>
                <div class="x30__bet">
                    @auth
                    @if(\Auth::user()->admin == 1)
                    <div class="plusBlock" onclick="goX100('100')">Go</div>
                    @endif
                    @endauth
                    <div onclick="disable(this);betX100('100')" class="x30__bet-heading is-ripples flare x100 d-flex align-center justify-space-between">
                        <span>X100</span>
                    </div>
                    <div class="x30__bet-info d-flex align-center justify-space-between">
                        <span class="d-flex align-center" style="color: #9EABCD;">
                            <svg class="icon small" style="margin-right: 8px;"><use xlink:href="images/symbols.svg#users"></use></svg>
                            <span data-playersX100=100></span>
                        </span>
                        <span class="d-flex align-center" style="font-weight: 600;">
                            <span data-sumBetsX100=100></span>
                            <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>
                        </span>
                    </div>
                    <div class="x100__bet-users x100">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
@if(\Auth::user()->admin == 1)
<script type="text/javascript">
    function startRoundX100() {
        const startButton = $('#x100_start_button')
        startButton.prop('disabled', true).text('Iniciando...')
        if (typeof toggleX100StartButton === 'function') {
            toggleX100StartButton(false)
        }
        $('#x100__text').html('Iniciando...')
        $('#x100__timer').html('15')

        $.post('/x100/start', {_token: csrf_token}).then(e => {
            if (e.success) {
                notification('success', e.mess)
                if (e.round && e.round.betting_ends_at && typeof startBettingPhase === 'function') {
                    startBettingPhase(e.round.betting_ends_at)
                }
                setTimeout(() => {
                    if (typeof getX100 === 'function') {
                        getX100(false)
                    }
                }, 500)
            }
            if (e.error || e.success === false) {
                notification('error', e.error || e.mess)
                if (typeof toggleX100StartButton === 'function') {
                    toggleX100StartButton(true)
                }
                startButton.prop('disabled', false).text('Empezar')
            }
        }).fail(() => {
            notification('error', 'No se pudo iniciar el round')
            if (typeof toggleX100StartButton === 'function') {
                toggleX100StartButton(true)
            }
            startButton.prop('disabled', false).text('Empezar')
        })
    }

    function goX100(coff) {
        param = {
            _token:csrf_token,
            coff: coff
        }

        $.post('/x100/go',param).then(e=>{
            if(e.success){
                notification('success',e.mess)
            }
            if(e.error){      
                notification('error',e.error)
            }
        })
    }
    function getX100Bonus(user_id, avatar){
        param = {
            _token:csrf_token,
            user_id, avatar
        }

        $.post('/x100/bonusgo',param).then(e=>{
            if(e.success){
                notification('success',e.mess)
            }
            if(e.error){      
                notification('error',e.error)
            }
        })
    }
</script>
@endif
@endauth

<script type="text/javascript">
    window.x100SingleMode = true
    window.x100IsSpinningSingle = false
    window.x100PendingHistory = null

    function resolveX100HistoryColorByCoff(coff) {
        const coffNum = Number(coff) || 0
        const colorMap = {
            2: '#1F2872',
            3: '#33C9C0',
            10: '#FF8049',
            15: '#7A49FF',
            20: '#FFD849',
            100: '#FF5247'
        }

        if (Object.prototype.hasOwnProperty.call(colorMap, coffNum)) {
            return colorMap[coffNum]
        }

        return '#64748B'
    }

    window.updateHistoryX100 = function (history) {
        const scroll = $('.x100__history-scroll')
        scroll.html('')

        ;(history || []).forEach((item, index) => {
            const randomRaw = String(item && item.random ? item.random : '').replace(/""/g, "''")
            const signature = item && item.signature ? item.signature : ''
            const coff = Number(item && item.coff ? item.coff : 0) || 0
            const rowId = item && item.id ? item.id : ('local_' + index)
            const bg = resolveX100HistoryColorByCoff(coff)

            scroll.append("<form action='https://api.random.org/verify' method='post' target='_blank'>\
                <input type='hidden' name='format' value='json'>\
                <input type='hidden' name='random' value='" + randomRaw + "' >\
                <input type='hidden' name='signature' value='" + signature + "'>\
                <div title='X" + coff + "' style='width:26px;height:26px;border-radius:6px;background:" + bg + ";display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:700;cursor:pointer;margin-right:6px;' onclick='$(`.btn_check_" + rowId + "`).click()'>x" + coff + "</div> <button type='submite' class='btn_check_" + rowId + "' style='display:none' ></button>\
                </form>\
                ")
        })
    }

    function setSelectedX100Coff(coff) {
        $('.x100 .x30__bet-heading').removeClass('x100-selected')
        const label = $('#x100_selected_coff')
        if (!coff) {
            label.hide().html('')
            return
        }
        $('.x100 .x30__bet-heading.x' + coff).addClass('x100-selected')
        label.show().html('Elegiste: X' + Number(coff))
    }
    window.setSelectedX100Coff = setSelectedX100Coff

    function renderX100SingleSession(session) {
        const betButtons = $('.x100 .x30__bet-heading')
        const timer = $('#x100__timer')
        const text = $('#x100__text')

        timer.hide()

        betButtons.css('pointer-events', '').removeClass('disabled')
        if (text.length && text.html() !== 'Girando...') {
            text.html('Elige tu apuesta')
        }
    }

    function playX100SpinResult(result, onFinished = null) {
        if (!result) {
            if (typeof onFinished === 'function') {
                onFinished()
            }
            return
        }
        window.x100IsSpinningSingle = true
        const number = Number(result.number) || 0
        const coff = Number(result.coff) || 0
        const baseAngle = -1.8
        const pointerOffset = 180
        const targetAngle = (typeof result.target_angle !== 'undefined' && result.target_angle !== null)
            ? (Number(result.target_angle) - pointerOffset)
            : (baseAngle + ((360 / 100) * number) - pointerOffset)
        const fallbackAngle = ((targetAngle % 360) + 360) % 360
        if (typeof startSpinningPhase === 'function') {
            startSpinningPhase(coff, fallbackAngle, null, null, 4)
        }
        const wheel = $('#x100__wheel')
        let done = false
        const finishAfterSpin = () => {
            if (done) {
                return
            }
            done = true
            window.x100IsSpinningSingle = false
            if (wheel.length) {
                wheel.off('transitionend.x100single')
            }
            if (typeof finishRound === 'function') {
                finishRound(coff)
            }
            if (window.x100PendingHistory && typeof updateHistoryX100 === 'function') {
                updateHistoryX100(window.x100PendingHistory)
                window.x100PendingHistory = null
            }
            if (typeof onFinished === 'function') {
                onFinished()
            }
        }

        if (wheel.length) {
            wheel.one('transitionend.x100single', finishAfterSpin)
        }
        setTimeout(finishAfterSpin, 4300)
    }
    window.playX100SpinResult = playX100SpinResult
    window.renderX100SingleSession = renderX100SingleSession

    function getX100(loadBets = false){
        $.post('/x100/get',{_token: csrf_token}).then(e=>{
            if(loadBets && e.success){
                $('.x100 .x100__bet-users').html('')
                e.success.forEach((e)=>{
                    e = e
                    class_dop = ''
                    if(e.user_id == USER_ID){
                        class_dop = 'img_no_blur'
                    }

                    dopText = ''
                    @auth
                    @if(\Auth::user()->admin == 1)
                    dopText = '<div class="dopPlusBetX100" onclick="getX100Bonus('+e.user_id+', `'+e.img+'`)">Bonus</div>'
                    @endif
                    @endauth

                    $('.x100 .x100__bet-users.x'+e.coff).prepend('<div data-user-id='+e.user_id+' class="x30__bet-user d-flex align-center justify-space-between">'+dopText+'\
                        <div class="history__user d-flex align-center justify-center">\
                        <div class="history__user-avatar '+class_dop+'" style="background: url('+e.img+') no-repeat center center / cover;"></div>\
                        <span>'+e.login+'</span>\
                        </div>\
                        <div class="x30__bet-sum d-flex align-center">\
                        <span>'+(Number(e.bet).toFixed(2))+'</span>\
                        <svg class="icon money" style="margin-left: 8px;"><use xlink:href="images/symbols.svg#coins"></use></svg>\
                        </div>\
                        </div>')


                })

                e.info.forEach((e)=>{
                    $('span[data-sumBetsX100='+e.coff+']').html((e.sum).toFixed(0))
                    $('span[data-playersX100='+e.coff+']').html(e.players)
                })


            }
            if (e.round && window.x100SingleMode !== true) {
                if (e.round.status === 'BETTING' && typeof startBettingPhase === 'function') {
                    startBettingPhase(e.round.betting_ends_at)
                }
                if (e.round.status === 'SPINNING' && typeof startSpinningPhase === 'function') {
                    const fallbackAngle = Math.floor(Math.random() * 360)
                    startSpinningPhase(e.round.result_coff, fallbackAngle, e.round.spinning_ends_at, e.round.id, 15)
                }
                if (e.round.status === 'FINISHED' && typeof finishRound === 'function') {
                    finishRound(e.round.result_coff)
                }
                if (e.round.status === 'WAITING' && typeof setX100WaitingState === 'function') {
                    setX100WaitingState()
                }
            }
            if (typeof renderX100SingleSession === 'function') {
                renderX100SingleSession(e.session || null)
            }
            if (window.x100IsSpinningSingle === true) {
                window.x100PendingHistory = e.history || []
            } else {
                updateHistoryX100(e.history)
            }
        })
    }
    getX100(true)
    if (window.x100PollInterval) {
        clearInterval(window.x100PollInterval)
    }
    window.x100PollInterval = setInterval(() => getX100(false), 1000)


</script>
<script type="text/javascript">
    
    $('.close').click(function(e) {
        setTimeout(() => {
            $('.overlayed, .popup, body').removeClass('active');
        }, 100)
        $('.overlayed').addClass('animation-closed')
        return false;
    });
    $('.overlayed').click(function(e) {
        var target = e.target || e.srcElement;
        if(!target.className.search('overlay')) {
            setTimeout(() => {
                $('.overlayed, .popup, body').removeClass('active');
            }, 100)
            $('.overlayed').addClass('animation-closed')
        } 
    }); 
    $('[rel=popup]').click(function(e) {
        showPopup($(this).attr('data-popup'));
        return false;
    });

    function showPopup(el) {
        if($('.popup').is('.active')) {
            $('.popup').removeClass('active');  
        }
        $('.overlayed, body, .popup.'+el).addClass('active');
        $('.overlayed').removeClass('animation-closed');
    }
</script>

@auth
<script type="text/javascript">
    socket.emit('subscribe', 'roomGame_1_{{\Auth::user()->id}}');
</script>
@endauth
