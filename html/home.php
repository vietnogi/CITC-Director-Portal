<h2>Home Page</h2>
<p>Hello world!</p>
<script type="text/javascript">
<!--
	//testing ajax
	/*
	$.ajax({
		type: 'POST'
		, url: FW.CR + '/ajax/test'
		, data: {abc: '123', xyz: 'abc'}
		, success: function(data){
			alert('success');	
		  }
		, complete: function(httpObj, textStatus){
			switch(1 * httpObj.status){
				case 301:
				case 302:
				case 0: //for ie
					try {
						var data = $.parseJSON(httpObj.responseText);
						window.location.href = data.location;
					} catch (err) {
						alert('Error trying to redirect.');
					}
				break;
				case 404:
					alert("Page Not Found");
				break;
			}
		  }
	});
	*/
//-->
</script>