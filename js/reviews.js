$(function()
{
	lookForHash();
});

function lookForHash()
{
	if (location.hash != "") {
		if ($(location.hash).get(0) === undefined) {} else
		{
			$(location.hash).addClass('hashed');
		}
	}
}
