var Changlog = function(callback){
    localStorage.getItem('LastVersion');
    $.get('/changelog.txt', function(txt){
        console.log(txt)
    });
}