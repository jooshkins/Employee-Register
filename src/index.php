<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <title>Directory</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="/styles.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="nav">
			<h1> 
				<span>🍕</span> 
				Rad Pizza Co. 
				<span>🎉</span>
			</h1>
			<ul class="nav">
				<li>
					<a href="index.php">
						<span>🏠</span> Employee Directory
					</a>
				</li>
				<li>
					<a href="add.php">
						<span>➕</span> Add Employee
					</a>
				</li>
			</ul>
		</div>
        <div class="container">
        <h2>Employee Directory</h2>
        <table>
            <tr>
                <th class="clickable">Employee Name (Last, First)</th>
                <th class="clickable">Location</th>
                <th class="clickable">Status</th>
                <th>Actions</th>
            </tr>
            <?php 
                include 'logic.php';
                getEmployees("ALL");
            ?>
        </table>
        </div>
    </body>
    <script>
        // for time sake, use existing sorting solution.
        // https://stackoverflow.com/questions/14267781/sorting-html-table-with-javascript/49041392#49041392

        var getCellValue = function(tr, idx){ return tr.children[idx].innerText || tr.children[idx].textContent; }

        var comparer = function(idx, asc) { return function(a, b) { return function(v1, v2) {
                return v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
            }(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));
        }};

        Array.from(document.querySelectorAll('th')).forEach(function(th) { th.addEventListener('click', function() {
                var table = th.closest('table');
                Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
                    .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                    .forEach(function(tr) { table.appendChild(tr) });
            })
        });
    </script>
</html>