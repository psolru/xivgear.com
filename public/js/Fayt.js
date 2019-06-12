window.Fayt = {
	timeout: null,
	template: `
		<% results.forEach(function(result){ %>
			<a
				class="d-flex align-items-center mb-2"
				<% if (options.link) { %>
					href="/Character/<%= result.ID %>"
				<% } %>
				<% if (options.onclick) { %>
					onclick="
						<%= options.onclick %>({id:'<%= result.ID %>', name:'<%= result.Name %>', server:'<%= result.Server %>', avatar:'<%= result.Avatar %>'});
					"
				<% } %>
			>
				<img class="mr-2" src="<%= result.Avatar %>">
				<div class="mr-2 server"><%= result.Server %></div>
				<div class="name"><%= result.Name %></div>
			</a>
		<% }); %>
	`,
	lastSearch: '',
	showResult: function(str, targetId, options) {
		if (options===undefined) {
			options = {
				link: true,
				onclick: false,
				onclickclose: false
			}
		}
		options.target = targetId

		let self = this

		if (self.lastSearch == str)
			return
		else
			self.lastSearch = str

		clearTimeout(self.timeout)
		self.timeout = setTimeout(function() {
			let target = $('#'+targetId)

			if (str.length == 0) {
				target.html('')
				return
			}
			target.html('<i class="fas fa-lg fa-spinner fa-pulse"></i>')
			target.addClass('d-block')


			let xhttp = new XMLHttpRequest()
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					let json = JSON.parse(this.responseText)
					target.html(
						ejs.render(self.template, { results: json.Results, options: options } )
					)
				}
			}
			xhttp.open("GET","https://xivapi.com/character/search?name=" + str, true)
			xhttp.send()

		}, 500)
	}
}