<?php 
//include_once 'includeAll.php';
?>
<html>
    <head>
        <title>title</title>
        <link href="style/testScroll.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
	<div class="scrollingtable">
		<div>
			<div>
				<table>
					<caption>Top Caption</caption>
					<thead>
						<tr>
            <th><div label="Column 1"></div></th>
            <th><div label="Column 2"></div></th>
            <th><div label="Column 3"></div></th>
							<th>
								<!--more versatile way of doing column label; requires 2 identical copies of label-->
								<div><div>Column 4</div><div>Column 4</div></div>
							</th>
              <th class="scrollbarhead"></th> <!--ALWAYS ADD THIS EXTRA CELL AT END OF HEADER ROW-->
						</tr>
					</thead>
					<tbody>
						<tr><td>Lorem ipsum</td><td>Dolor</td><td>Sit</td><td>Amet consectetur</td></tr>
					</tbody>
				</table>
			</div>
			Faux bottom caption
		</div>
	</div>
</body>
<!--[if lte IE 9]><style>.scrollingtable > div > div > table {margin-right: 17px;}</style><![endif]-->
</html>