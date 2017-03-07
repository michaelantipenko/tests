(function () {
    var fetchButton = document.getElementById('fetch');
    var iFrameButton = document.getElementById('iframe');
    var jsonpButton = document.getElementById('jsonp');
    var result = document.getElementById('result');

    /**
     * Loads data from remote server through fetch. This method will be work if
     * remote server set header Access-Control-Allow-Origin: *
     */
    fetchButton.addEventListener('click', function (event) {
        fetch('https://api.github.com/repos/laravel/laravel')
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                result.innerHTML = '<h2>Fetch</h2>' + JSON.stringify(json);
            })
    });

    /**
     * Loads data from server through hidden iframe.
     */
    iFrameButton.addEventListener('click', function (event) {
        var iframe = document.createElement('iframe');
        iframe.id = 'ajax-iframe';
        iframe.style.display = 'none';
        iframe.src = 'data.json';

        iframe.onload = function () {
            result.innerHTML = '<h2>IFrame</h2>' + iframe.contentWindow.document.getElementsByTagName('pre')[0].innerText;
            iframe.remove();
        };

        document.body.appendChild(iframe);
    });

    /**
     * Loads data from remove server through JSONP, but remote server have to
     * maintain JSONP requests.
     */
    jsonpButton.addEventListener('click', function (event) {
        var script = document.createElement('script');
        script.id = 'jsonp-script';
        script.src = "https://api.vk.com/method/users.get?user_ids=210700286&fields=bdate&v=5.62&callback=jsonpCallback";
        document.body.appendChild(script);
    });

    window.jsonpCallback = function (response) {
        result.innerHTML = '<h2>JSONP</h2>' + JSON.stringify(response);
        document.getElementById('jsonp-script').remove();
    };
})();
