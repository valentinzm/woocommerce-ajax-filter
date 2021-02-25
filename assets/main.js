
    
    document.querySelectorAll('.page-link').forEach( (item) => {
        item.addEventListener('click', ajaxFilter)
    });
    
    document.querySelectorAll('.btn-filter').forEach( (btn) => {
        btn.addEventListener('click', ajaxFilter );
    });
    
    const loadmore  = document.querySelector('.loadmore'); 
    const firstLink = document.querySelector('.page-item');

    if( loadmore !== null ) {
        loadmore.addEventListener('click', ajaxFilter );
    } else if ( firstLink !== null ) {
        firstLink.classList.add('active');
    } else {
        console.log('ничгео не делаем');
    }



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
        paged = this.dataset.page;
    } else if ( this.classList.contains('loadmore') ) {
        this.remove();
        paged = this.dataset.page;
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
    data.append('pagination', params_json.pagination );

    const admin_ajax_url = plugin.ajax_url;

    fetch(admin_ajax_url, {
            method: 'post',
            body: data
        })
        .then((response) => {
          return response.text();
        })
        .then((data) => {
            if( this.classList.contains('loadmore') ){
                container.innerHTML += data;
            } else {
                container.innerHTML = data;
            }
            
            const more = document.querySelector('.loadmore');
            if( more !== null ) {
                more.addEventListener('click', ajaxFilter );
            }
            
            document.querySelectorAll('.btn-filter').forEach( (btn) => {
                btn.addEventListener('click', ajaxFilter );
            });
            document.querySelectorAll('.page-link').forEach( (item) => {
                item.addEventListener('click', ajaxFilter)
            });
            
            document.querySelectorAll('.page-link').forEach(item => {
                if (item.textContent == paged) {
                    item.parentElement.classList.add('active'); 
                }
            });
            
            

        })
        .catch(() => { console.log('error') })
        .finally(() => {});
            

        

}