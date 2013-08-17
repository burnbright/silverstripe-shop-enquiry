<% if Title %><h1>$Title</h1><% end_if %>
<h2>$Name</h2>
<% if Message %><p>$Message</p><% end_if %>
<% if Items %>
	<p>The following item<% if Items.Plural %>s are<% else %> is<% end_if %> being enquired about:</p>
	<table>
		<thead>
			<tr>
				<th>Product</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<% loop Items %>
				<tr>
					<td><a href="$Link">$TableTitle</a>
						<% if SubTitle %><br/>$Subtitle<% end_if %></td>
					<td>$UnitPrice.Nice</td>
					<td>$Quantity</td>
					<td>$Total.Nice</td>
				</tr>
			<% end_loop %>
		</tbody>
	</table>
<% end_if %>