$(function() {
	insertSearch();
});

function insertSearch()
{
	var search = decodeURI(location.href.split('/')[location.href.split('/').length-1]);
	var sInput = $('input.search-text');
	sInput.val(search);
}