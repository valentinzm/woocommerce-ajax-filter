document.querySelectorAll('.page-link').forEach( (item) => {
    item.addEventListener('click', ajaxFilter)
});

document.querySelectorAll('.btn-filter').forEach( (btn) => {
    btn.addEventListener('click', ajaxFilter );
});
document.querySelector('.page-item').classList.add('active');
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


    if( this.classList.contains('page-link') ){
        paged = this.textContent;
    } else {
        paged = 1;
    }
    
    const filter_params = document.querySelector('.filters').dataset.filter;
    let params_json = JSON.parse(filter_params);

                
    let data = new FormData();
    data.append('action', 'filtered');
    data.append('nonce', plugin.nonce);
    data.append('paged',  paged);
    data.append('terms', terms.toString() );

    data.append('post_type', params_json.post_type );
    data.append('amount', params_json.amount );

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
            console.log(paged);
            document.querySelectorAll('.page-link').forEach(item => {
                if (item.textContent == paged) {
                    item.parentElement.classList.add('active'); 
                }
            })
        })
        .catch(() => { console.log('error') })
        .finally(() => { console.log() });

}