/**
 * Created by Диана on 09.05.2015.
 */
$(document).ready(function() { // вся магия после загрузки страницы
    $('a.go').click( function(event){ // ловим клик по ссылки с id="go"
        event.preventDefault(); // выключаем стандартную роль элемента
        $("#pdf_hb").val($(this).data("hb"));
        $("#pdf_to").val($(this).data("to"));
        $('#overlay').fadeIn(400, // сначала плавно показываем темную подложку
            function(){ // после выполнения предъидущей анимации
                $('#modal_form')
                    .css('display', 'block') // убираем у модального окна display: none;
                    .animate({opacity: 1, top: '50%'}, 200); // плавно прибавляем прозрачность одновременно со съезжанием вниз
            });
    });
    /* Закрытие модального окна, тут делаем то же самое но в обратном порядке */
    $('#modal_close, #overlay').click( function(){ // ловим клик по крестику или подложке
        $('#modal_form')
            .animate({opacity: 0, top: '45%'}, 200,  // плавно меняем прозрачность на 0 и одновременно двигаем окно вверх
            function(){ // после анимации
                $(this).css('display', 'none'); // делаем ему display: none;
                $('#overlay').fadeOut(400); // скрываем подложку
                $("#pdf_email").val("");
            }
        );
    });

    $("#generate_pdf").click( function(event){ // ловим клик по ссылки с id="go"
            event.preventDefault(); // выключаем стандартную роль элемента
            curEmail = $("#pdf_email").val();
            curType = $("#pdf_type").val();
            curApp = $("#pdf_app").val();
            curHB = $("#pdf_hb").val();
            toPage = '';
            if($("#pdf_to").val() == 'shedule'){
                toPage = "/ajax/all_pdf_shedule.php";
            }
            else{
                toPage = "/ajax/all_pdf_wishlist.php";
            }
            if(curEmail == ''){
                $("#pdf_error").text("Вы не ввели email для отправки.");
                $("#pdf_error").show();
            }
            else{
                $("#pdf_error").removeClass("error");
                $("#pdf_error").text("На ваш email будет отправлена ссылка на архивю");
                $("#pdf_error").addClass("sucsess");
                var req = $.post(toPage, { type: curType, app: curApp, email: curEmail, hb: curHB} );
                setTimeout(function () {
                    $('#modal_form')
                        .animate({opacity: 0, top: '45%'}, 200,  // плавно меняем прозрачность на 0 и одновременно двигаем окно вверх
                        function(){ // после анимации
                            $(this).css('display', 'none'); // делаем ему display: none;
                            $('#overlay').fadeOut(400); // скрываем подложку
                            $("#pdf_error").removeClass("sucsess");
                            $("#pdf_error").addClass("error");
                            $("#pdf_error").hide();
                            $("#pdf_email").val("");
                        }
                    );
                }, 1500);

            }
        }

    );
});