<% if Title %><h1>$Title</h1><% end_if %>
<h2>$Name: <a href="mailto:$Email">$Email</a></h2>

<% if Message %><p>$Message</p><% end_if %>
<% if Items %>
	<p><strong>The following item<% if Items.Plural %>s are<% else %> is<% end_if %> being enquired about:</strong></p>
	<table width="100%" class="ss-gridfield-table">
		<thead>
			<tr class="title">
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
		<tfoot>
			<td colspan="4"></td>
		</tfoot>
	</table>
<% end_if %>