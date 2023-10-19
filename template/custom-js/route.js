// Uri Segement
var $host = window.location.origin == 'http://localhost';
if ($host) {
    var _uri = `${window.location.origin}/emonev`;
} else {
    var _uri = `${window.location.origin}`;
}
var _uriSegment = window.location.pathname.split('/');
console.log('Location Origin', _uri);
console.log(_uriSegment);
    
// Params
var queryString = window.location.search;
var urlParams = new URLSearchParams(queryString);
console.log('Params', urlParams);