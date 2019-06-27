<h1>Bulk Product Upload Information</h1>
<p>This feature is to help you to bulk upload product information. For example, if you have your product listed in a specific program (eg. MS
Access, MS Excel, etc) you can easily export them to your product database without having to enter them manually one-by-one.</p>
<h2>Step-by-Step</h2>
<ol>
	<li>In your database program, export the database to CSV (Comma Separated Values) format. Most database &amp; spreadsheet programs
	have this feature.</li>
	<li>Then edit the CSV following below format. You may need to use a spreadsheet program to do this. With a spreadsheet program, you
	may only need to move, copy &amp; replace some columns.</li>
	<li>Go to ACP &gt; Products &gt; Bulk Upload (if you can read this guide, you are probably already there).</li>
	<li>Upload the CSV file. Click submit.</li>
	<li>Done.</li>
</ol>

<h2>The Format</h2>
<p>You should order entry to this format:</p>
<table class="table table-bordered">
	<tr>
		<th>Category ID</th>
		<th>SKU</th>
		<th>Product Title</th>
		<th>Price</th>
		<th>MSRP Price</th>
		<th>Details</th>
		<th>Weight</th>
		<th>Brand/Manufacturer</th>
		<th>Stock</th>
		<th>Tax Class</th>
	</tr>
</table>
<p>Where:</p>
<ul>
	<li><b>Category ID</b> = enter category id of the product. <span style="color:#f00">&bull;</b></li>
	<li><b>SKU</b> = SKU code (must be unique).</li>
	<li><b>Product Title</b> = title of product. <span style="color:#f00">&bull;</b></li>
	<li><b>Price</b> = price of product, enter only number (no currency), eg: 12345.67. <span style="color:#f00">&bull;</b></li>
	<li><b>MSRP Price</b> = MSRP price.</li>
	<li><b>Details</b> = detail/description, you can also use HTML tags.</li>
	<li><b>Weight</b> = product's weight (in weight unit you set in ACP).</li>
	<li><b>Brand/Manufacturer</b> = manufacturer id.</li>
	<li><b>Stock</b> = number of stock, enter only integers, eg: 12345.</li>
	<li><b>Tax Class</b> = tax class.</li>
</ul>
<p><span style="color:#f00">&bull;</b> required fields</p>

<h2>Sample</h2>
<p>In the spreadsheet program:</p>
<table class="table table-bordered">
	<tr>
		<th>Category ID</th>
		<th>SKU</th>
		<th>Product Title</th>
		<th>Price</th>
		<th>MSRP Price</th>
		<th>Details</th>
		<th>Weight</th>
		<th>Brand/Manufacturer</th>
		<th>Stock</th>
		<th>Tax Class</th>
	</tr>
	<tr>
		<td>1</th>
		<td>BJ123-XP</th>
		<td>Test Product</th>
		<td>123.5</th>
		<td>150</th>
		<td>This is a complete description</th>
		<td>3</th>
		<td>1</th>
		<td>10</th>
		<td>1</th>
	</tr>
	<tr>
		<td>5</th>
		<td></th>
		<td>Just Another Test Product</th>
		<td>53</th>
		<td>0</th>
		<td>This is a complete description</th>
		<td>1.5</th>
		<td>9</th>
		<td>20</th>
		<td>1</th>
	</tr>
</table>
<p>Save as CSV, result (you can also get the sample <a href="../misc/bulk_demo.csv">from here</a>):</p>
<pre>Category ID,SKU,Product Title,Price,MSRP Price,Details,Weight,Brand/Manufacturer,Stock,Tax Class
1,BJ123-XP,Test Product,123.5,150,This is a complete description,3,1,10,1
5,,Just Another Test Product,53,0,This is a complete description,1.5,9,20,1</pre>
<p>Upload the file to ACP. And you are done.</p>
</body></html>