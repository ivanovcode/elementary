document.addEventListener('DOMContentLoaded', function(){
    let links_prop = 'elementary-stream-id';
    let links_ = document.querySelectorAll('[' + links_prop + ']');
    Array.prototype.forEach.call(links_, function(link_){
        link_.addEventListener('click', function() {
            console.log(link_.getAttribute(links_prop));

            var newName = 'John Smith',
                xhr = new XMLHttpRequest();

            xhr.open('POST', location.protocol + '//' + location.host + location.pathname + '?m=api&stream=' + link_.getAttribute(links_prop));
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200 && xhr.responseText !== newName) {
                    console.log('Something went wrong.  Name is now ' + xhr.responseText);
                }
                else if (xhr.status !== 200) {
                    console.log('Request failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.send(encodeURI('name=' + newName));


        });
    });
});