;(function($){
    console.log('Hi mehedi');
    var API_URL= 'https://portal.firstdebit.de/fdc/fc.php';
    var username = cbdShop.username;
    var pass = cbdShop.pass;
    var connect = (cbdShop.connect == 'test')? 'MY':'MC';


    var finalURl = API_URL+'?'+username+'&pa='+pass+'&cd='+connect;
        finalURl += '&name=mehedi';
        console.log(finalURl);


})(jQuery)