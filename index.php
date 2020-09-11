<?php 
	require 'header.php'; 
	require 'nav.php'; 
?>

	<section id="intro" class="row">
		<div class="small-12 columns">
			<p>&nbsp;</p>
			<p class="callout alert"><b>PLEASE NOTE:</b> This database is not currently fully operational and is still in testing mode; please contact the Indiana Room at (812) 285-5641 or indianaroom@jefflibrary.org if you are searching for obituaries</p>
           	<p>This is the web application to allow users to search the obituaries indexed in the various newspapers available on microfilm at the <a href="http://jefflibrary.org" target="_blank">Jeffersonville Township Public Library</a>. You can use the search box in the navigation bar if you want to conduct a simple keyword search or you can use the advanced search form below to conduct a more complex search.</p>
			<p><!-- <b>&#x261E;</b> --> <i class="fa fa-hand-o-right fa-lg"></i> &nbsp; Please visit the <a href="about.php">about page</a> to find out what years of which newspapers have been added to this index so far.</p>			
		</div>
	</section>

	<section id="advsearch" class="row">
			<article class="small-12 medium-8 columns callout">
				<h5>Search for Obituaries:</h5>
				<form action="search.php" id="advancedSearch" method="get" onsubmit="stringCleaning()">
					<div class="row">
						<div class="small-11 medium-5 columns">
							<label><b>First Name</b></label>
							<input type="text" placeholder="First name" name="fn" id="fn" />
						</div>
						<div class="small-1 medium-1 columns text-left align-middle" id="fnVar"><p id="error-firstname" class="warning-input"><span class="has-tip top" data-tooltip aria-haspopup="true" tabindex="1" data-disable-hover="false" title="You may only use letters and wild cards in the First Name field"><i class="fa fa-exclamation-triangle fa-lg"></i></span></p></div>
						<div class="small-11 medium-5 columns">
							<label><b>Last Name</b></label>
							<input type="text" placeholder="Last name" name="ln" id="ln" />
						</div>
						<div class="small-1 medium-1 columns text-left align-middle" id="lnVar"><p id="error-lastname" class="warning-input"><span class="has-tip top" data-tooltip aria-haspopup="true" tabindex="2" data-disable-hover="false" title="You may only use letters and wild cards in the Last Name field"><i class="fa fa-exclamation-triangle fa-lg"></i></span></p></div>
					</div>
					<div class="row">
						<div class="small-11 medium-5 columns">
							<label><b>Church*</b></label>
							<input type="text" placeholder="Church" name="ch" id="ch" />
						</div>
						<div class="small-1 medium-1 columns text-left align-middle" id="chVar"><p id="error-church" class="warning-input"><span class="has-tip top" data-tooltip aria-haspopup="true" tabindex="3" data-disable-hover="false" title="You may only use letters, numbers, periods, apostrophes, and wild cards in the Church field"><i class="fa fa-exclamation-triangle fa-lg"></i></span></p></div>
						<div class="small-11 medium-5 columns">
							<label><b>Burial*</b></label>
							<input type="text" placeholder="Burial" name="bur" id="bur" />
						</div>
						<div class="small-1 medium-1 columns text-left align-middle" id="burVar"><p id="error-burial" class="warning-input"><span class="has-tip top" data-tooltip aria-haspopup="true" tabindex="4" data-disable-hover="false" title="You may only use letters, numbers, periods, apostrophes, and wild cards in the Burial field"><i class="fa fa-exclamation-triangle fa-lg"></i></span></p></div>
					</div>
					<div class="row align-middle">
						<div class="small-12 large-4 columns" id="showYears">
							<div class="button" id="buttonShowYr">Add Publication Year &nbsp; <i class="fa fa-plus-square fa-lg"></i></div>
						</div>
						<div class="small-12 large-4 columns" id="hideYears">
							<div class="button" id="buttonHideYr">Hide Publication Year &nbsp; <i class="fa fa-minus-square fa-lg"></i></div>
						</div>
						<div class="small-12 large-8 columns">							
							<label id="pubLabel"><b>Publication Year(s)</b></label>
							<input type="hidden" id="pubselected" name="pubselected" value="hidden" />
							<div class="row">
								<div class="small-12 columns">
									<div class="row" id="firstYear">
										<div class="small-3 large-4 columns">
											<select name="yt" id="yt">
												<option value="in">in</option>
												<option value="before">before</option>
												<option value="after">after</option>
												<option value="between">between</option>
											</select>
										</div>
										<div class="small-4 large-4 columns">
											<select name="smn" id="smn">
												<option value="all" selected>All Months</option>
												<option value="space1" disabled>-----</option>
												<option value="01">January</option>
												<option value="02">February</option>
												<option value="03">March</option>
												<option value="04">April</option>
												<option value="05">May</option>
												<option value="06">June</option>
												<option value="07">July</option>
												<option value="08">August</option>
												<option value="09">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select>
										</div>
										<div class="small-4 large-3 columns">
											<input type="number" maxlength="4" placeholder="1872" name="syr" id="syr"/>
										</div>
										<div class="small-1 large-1 columns text-left align-middle" id="fyVar"><p id="error-firstyear" class="warning-input"><span class="has-tip top" data-tooltip aria-haspopup="true" tabindex="5" data-disable-hover="false" title="You must enter a year if you want to search by publication date"><i class="fa fa-exclamation-triangle fa-lg"></i></span></p></div>
									</div>
									<div class="row" id="between">
										<div class="small-3 large-4 columns text-right align-middle">
											<p>and</p>
										</div>
										<div class="small-4 large-4 columns" id="month2">
											<select name="emn" id="emn">
												<option value="all" selected>All Months</option>
												<option value="space1" disabled>-----</option>
												<option value="01">January</option>
												<option value="02">February</option>
												<option value="03">March</option>
												<option value="04">April</option>
												<option value="05">May</option>
												<option value="06">June</option>
												<option value="07">July</option>
												<option value="08">August</option>
												<option value="09">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select>
										</div>
										<div class="small-4 large-3 columns" id="year2">
											<input type="number" maxlength="4" placeholder="1916" name="eyr" id="eyr"/>
										</div>
										<div class="small-1 large-1 columns text-left align-middle" id="eyVar"><p id="error-endyear" class="warning-input"><span class="has-tip top" data-tooltip aria-haspopup="true" tabindex="5" data-disable-hover="false" title="You must enter a 2nd year if you want to search between 2 publication dates"><i class="fa fa-exclamation-triangle fa-lg"></i></span></p></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="small-12 large-8 large-offset-4 columns" id="pubyearErr"></div>
					</div>
					<div class="row">
						<div class="small-12 columns text-center">
							<input type="submit" class="large button" id="advsubmit" value="Submit Search" disabled>
						</div>
					</div>
				</form>
				<small>* These fields were not indexed in every year so try a broader search if you are having difficulty</small>
			</article>
			<aside class="small-12 medium-4 columns">
				<div class="callout secondary">
					<h5><i class="fa fa-lightbulb-o fa-lg" style="color: orange;"></i> &nbsp; Search Tip</h5>
					<dl>
						<dt>Use the wildcard <kbd class="primary">%</kbd> to broaden your search</dt>
						<dd>For example, searching <kbd class="primary">Yar%</kbd> in the surname field will return:
							<ul>
								<li>Yarber</li>
								<li>Yarbro</li>
								<li>Yarbrough</li>
								<li>Yarling</li>
							</ul>
						</dd>
						<dt>Check multiple church spellings</dt>
						<dd>Different indexers referred to churches differently, so check <kbd class="primary">St. Anthony</kbd> as well as <kbd class="primary">St. Anthony's</kbd> or use a wildcard to search for <kbd class="primary">St. Anthony%</kbd></dd>
					</dl>
				</div>
			</aside>

    </section>

<?php require 'footer.php'; ?>
