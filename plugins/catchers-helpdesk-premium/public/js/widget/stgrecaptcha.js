
var widget = parent.window.WidgetHelpCatcher;
window.mycaptchaid = null;


function live (eventType, elementId, cb) {
    document.addEventListener(eventType, function (event) {
        var el = event.target
            , found;

        while (el && !(found = el.id === elementId)) {
            el = el.parentElement;
        }

        if (found) {
            cb.call(el, event);
        }
    });
}

function stgSubmit(){
    widget.handlerSubmit();

}

live('submit','stgh-help-catcher-form',function (e) {

    document.getElementById('stgh-help-catchers-submit').setAttribute('disabled','disabled');

    e.preventDefault();


    if (widget.validationForm()) {

        if(window.mycaptchaid !== null)
        {
            grecaptcha.reset(window.mycaptchaid);
        }
        else {
            window.mycaptchaid = grecaptcha.render('inrecaptcha', {
                'sitekey': widget.rkey,
                'callback': 'stgSubmit',
                'size': 'invisible',
                'badge': 'inline'
            });
        }

        grecaptcha.execute(window.mycaptchaid);
    }else{
        document.getElementById('stgh-help-catchers-submit').removeAttribute('disabled');
        return false;
    }
});
