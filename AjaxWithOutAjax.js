(function () {
    var url = 'data.json';
    var fetchButton = document.getElementById('fetch');
    var iFrameButton = document.getElementById('iframe');
    var result = document.getElementById('result');

    /**
     * Loads data from server through fetch.
     */
    fetchButton.addEventListener('click', function (event) {
        fetch(url)
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
        iframe.src = url;

        iframe.onload = function () {
            result.innerHTML = '<h2>IFrame</h2>' + iframe.contentWindow.document.getElementsByTagName('pre')[0].innerText;
            iframe.remove();
        };

        document.body.appendChild(iframe);
    });
})();
