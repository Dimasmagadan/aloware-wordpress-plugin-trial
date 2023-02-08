// load more
document.addEventListener('DOMContentLoaded', function() {
    var loadBtn = document.querySelector('.aloware-load');
    var postsContainer = document.querySelector('.posts-container');
    var template = document.querySelector('#aloware-tpl').innerHTML;
    var templateImg = document.querySelector('#aloware-img-tpl').innerHTML;

    loadBtn.addEventListener('click', function(event) {
        event.preventDefault();
        var currentPage = parseInt(loadBtn.getAttribute('data-page')) + 1;
        var filterBy = document.querySelector('#filter-ideas-by').value;

        var filterByCategoryInputs = document.querySelectorAll('#filter-ideas-by-category input[name="idea_category"]');
        var filterByCategory = -1;

        filterByCategoryInputs.forEach(input => {
            if (input.checked) {
                filterByCategory = input.value;
            }
        });
        
        fetch(aloware.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=aloware_load_more_ideas&nonce=${aloware.nonce}&page=${currentPage}&filterBy=${filterBy}&filterByCategory=${filterByCategory}`
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(posts) {
            if (!posts.success) {
                throw console.error('No posts');
            }

            posts.data.content.forEach(function(post) {
                var html = template;
                html = html.replace('{{title}}', post.title);
                html = html.replace('{{link}}', post.link);
                html = html.replace('{{content}}', post.content);
                
                var img = '';
                if (post.img) {
                    img = templateImg;
                    img = img.replace('{{img}}', post.img.src);
                    img = img.replace('{{width}}', post.img.width);
                    img = img.replace('{{height}}', post.img.height);
                    img = img.replace('{{srcset}}', post.img.srcset);
                    img = img.replace('{{sizes}}', post.img.sizes);
                }
                html = html.replace('{{img}}', img);
                
                var li = document.createElement('li');
                li.className = 'mb-4 p-2 border border-grey-light hover:bg-grey-light';
                li.innerHTML = html;

                postsContainer.appendChild(li);

                if ( posts.data.nextPage ) {
                    loadBtn.setAttribute('data-page', currentPage);
                } else {
                    loadBtn.innerHTML = 'No more posts';
                    loadBtn.className = 'cursor-default';
                    loadBtn.setAttribute('disabled', 'disabled');
                }
            });
        })
        .catch(function(error) {
            console.error(error);
        });
    });
});

