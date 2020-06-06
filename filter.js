function singleParametr(option) {
    return `<div class="param">${option}</div>`
}


let form = document.querySelector('#filter');
form.addEventListener('change', shopFilter);

function shopFilter(e) {

    let params = document.querySelector('.filter-params');
    let list = [];
    document.querySelectorAll('#filter input:checked').forEach(function(e) {
        let name = e.dataset.name;
        list.push(name);
        const create_params = list.map(item => singleParametr(item));
        let htmlParametrs = create_params.join(' ');
        params.innerHTML = htmlParametrs;
    });

    document.querySelectorAll('.param').forEach(function(e) {
        e.addEventListener('click', removeParam);
    });


    var filter = jQuery('#filter');
    jQuery.ajax({
        url: filter.attr('action'),
        data: filter.serialize(), // form data
        type: filter.attr('method'), // POST
        beforeSend: function(xhr) {
            jQuery('#response').addClass('processing'); // changing the button label
        },
        success: function(data) {

            jQuery('#response').html(data); // insert data
            jQuery('#response').removeClass('processing'); // changing the button label
        }
    });
    return false;
} //shopFilter



document.querySelectorAll('.param').forEach(function(e) {
    e.addEventListener('click', removeParam);
});

function removeParam(e) {
    this.remove();
    let parametr = 'input[data-name="' + this.textContent + '"]';
    let removedInput = document.querySelector(parametr);
    removedInput.checked = false;
    shopFilter();

}