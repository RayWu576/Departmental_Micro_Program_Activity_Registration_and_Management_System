
function updateTimeOnServer() {
    fetch('update_database.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log('Server response:', data);
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });
}

function kk() { 
    console.log(Date());
}

//setInterval(updateTimeOnServer, 10000); // 每10s執行一次
setInterval(kk, 60000); // 每10s執行一次
window.onload = function () {
    updateTimeOnServer();

    setInterval(updateTimeOnServer, 60000);
};

// function updateTimeOnServer() {
//     var xhr = new XMLHttpRequest();
//     xhr.open('GET', 'update_database.php', true);

//     xhr.onload = function () {
//         if (xhr.status >= 200 && xhr.status < 300) {
//             console.log('Server response:', xhr.responseText);
//         } else {
//             console.error('Error during XMLHttpRequest. Status:', xhr.status, 'Text:', xhr.statusText);
//         }
//     };

//     xhr.onerror = function () {
//         console.error('Network error during XMLHttpRequest.');
//     };

//     xhr.send();
// }

// window.onload = function () {
//     updateTimeOnServer();

//     setInterval(updateTimeOnServer, 60000);
// };
