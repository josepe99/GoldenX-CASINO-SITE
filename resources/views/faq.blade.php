<div class="wrapper">
    <div style="margin-top: 20px;" class="faq d-flex flex-column">
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>¿Qué es SO-YOU-START.RU?</span>
            </div>
            <div class="faq__item-body">
                <p>SO-YOU-START.RU es un servicio de juegos instantáneos.</p>
            </div>
        </div>
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>¿Cómo funciona el sistema de referidos?</span>
            </div>
            <div class="faq__item-body">
                <p>Recibes +10% de cada depósito de tu referido. <br>
                Si alcanzas cierta cantidad de referidos, puedes usar un giro gratis de ruleta y obtener bono.</p>
            </div>
        </div>
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>¿Cuánto tarda un retiro?</span>
            </div>
            <div class="faq__item-body">
                <p>El pago tarda de 1 minuto a 24 horas desde la solicitud. <br>
                En algunos casos puede tardar hasta 2 días.</p>
            </div>
        </div>
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>¿Cuál es el retiro mínimo?</span>
            </div>
            <div class="faq__item-body">
                <p>El retiro mínimo es de 100 monedas.</p>
            </div>
        </div>
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>Mi retiro fue rechazado, ¿qué hago?</span>
            </div>
            <div class="faq__item-body">
                <p>Probablemente ingresaste datos incorrectos o incumpliste las reglas.</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   $('.faq__item .faq__item-heading').click(function(e){
    e.preventDefault();
    if($(this).parent().hasClass('faq__item--opened')) {
        $(this).parent().removeClass('faq__item--opened').css({'max-height':'60px'});
    } else {
        $('.faq__item.faq__item--opened').removeClass('faq__item--opened').css({'max-height':'60px'});
        $(this).parent().addClass('faq__item--opened').css({'max-height': $(this).parent()[0].scrollHeight});
    }
});
</script>