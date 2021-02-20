document.querySelectorAll('.page-link').forEach( (item) => {
    item.addEventListener('click', ajaxFilter)
});

document.querySelectorAll('.btn-filter').forEach( (btn) => {
    btn.addEventListener('click', ajaxFilter );
});
const container = document.querySelector('#filter');
function ajaxFilter( event ){
    event.preventDefault();

    let terms = [];
    if( !this.classList.contains('btn-danger') ){
        this.classList.add('btn-danger');
    } else {
        this.classList.remove('btn-danger');
    }
    
    document.querySelectorAll('.btn-danger').forEach( (btn) => {
        terms.push( btn.dataset.term )
    });
    
    let data = new FormData();
    data.append('action', 'filtered');
    data.append('nonce', plugin.nonce);
    data.append('paged', this.textContent );
    data.append('terms', terms.toString() );

    const admin_ajax_url = plugin.ajax_url;

    fetch(admin_ajax_url, {
            method: 'post',
            body: data
        })
        .then((response) => {
          return response.text();
        })
        .then((data) => {
            container.innerHTML = data;
            
            document.querySelectorAll('.btn-filter').forEach( (btn) => {
                btn.addEventListener('click', ajaxFilter );
            });
            document.querySelectorAll('.page-link').forEach( (item) => {
                item.addEventListener('click', ajaxFilter)
            });
        })
        .catch(() => { console.log('error') })
        .finally(() => { console.log('finally') });

}