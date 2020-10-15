document.querySelector('.filter__search').addEventListener('input', blogFilter);
document.querySelectorAll('.filter__item').forEach((btn) => { btn.addEventListener('click', blogFilter) });
document.querySelector('.blog__loadmore').addEventListener('click', load);


function blogFilter(event) {
    event.preventDefault();

    const blogContainer = document.querySelector('#response');

    let search = document.querySelector('.filter__search').value;
    console.log(search);

    if (this.classList.contains('filter__active')) {
        this.classList.remove('filter__active');
    } else {
        this.classList.add('filter__active');
    }
    let
        data = new FormData();
    data.append('action', 'blog_filter');
    data.append('page', 1);
    data.append('search', search);

    let categories = [];
    document.querySelectorAll('.filter__active').forEach(function(e) {
        categories.push(e.dataset.cat);
    });
    data.append('categories', categories);

    const admin_url = '/wp-admin/admin-ajax.php';
    fetch(admin_url, {
            method: 'post',
            body: data
        })
        .then(response => response.text())
        .then(text => {
            blogContainer.innerHTML = text;
            document.querySelector('.blog__loadmore').addEventListener('click', load);
        })
}


function load(event) {
    event.preventDefault();
}