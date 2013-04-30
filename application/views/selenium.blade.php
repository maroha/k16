<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Selenium Test Results - {{ date(DATE_RFC1123) }}</title>
	<meta name="viewport" content="width=device-width">

	<link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">
</head>
<body data-spy="scroll" data-target=".main-menu">
	<div class="container">
		<h1>Testing results for {{ date(DATE_RFC1123) }}</h1>
		<div class="row-fluid">
			<div class="span3">
				<div class="span3" data-spy="affix" data-offset-top="0">
					<div class="well well-small main-menu">
						<ul class="nav nav-list">
							<li class="nav-header">Quick jump</li>
							<li class="active"><a href="#summary">Summary</a></li>
							<li class="nav-header">Tests</li>
							@foreach($results as $id => $result)
							<li>
								<a href="#test-{{ $id }}">
									{{{ $tests[$id]["name"] }}}
									@if($result["success"])
										<span class="label label-success">Success</span>
									@else
										<span class="label label-important">Failure</span>
									@endif
								</a>
							</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
			<div class="span9">
				<a name="summary"></a>
				<div id="summary">
					<h2>Summary</h2>
					@if($summary["failure"] == 0)
						<div class="alert alert-success">
							<h3>Tests success!</h3>
					@else
						<div class="alert alert-error">
							<h3>Tests failure!</h3>
					@endif
						<p>Successes: {{ $summary["success"] }} / Failures: {{ $summary["failure"] }}</p>
					</div>
				</div>
				@foreach($results as $id => $result)
				<a name="test-{{ $id }}"></a>
				<div id="test-{{ $id }}">
					@if($result["success"])
						<h2 class="text-success">Test #{{$id}}: {{{ $tests[$id]["name"] }}}</h2>
						<p class="text-success">Test success!</p>
					@else
						<h2 class="text-error">Test #{{$id}}: {{{ $tests[$id]["name"] }}}</h2>
						<p class="text-error">Test failed!</p>
						<div class="alert alert-error">
							<p>{{ $result["error"]->getMessage() }}</p>
							@if($result["error"]->getPrevious())
								@if($result["error"]->getPrevious() instanceof WebDriverException)
									<p>{{ $result["error"]->getPrevious()->getResults()["state"] }}</p>
								@else
									<p>{{ $result["error"]->getPrevious()->getMessage() }}</p>
								@endif
							@endif
						</div>
					@endif
					<ul class="nav nav-tabs">
						<li class="active"><a href="#test-{{ $id }}-log" data-toggle="tab">Log</a></li>
						@if(!$result["success"])
							@if(isset($result["error"]->data["screenshot"]))
								<li><a href="#test-{{ $id }}-screenshot" data-toggle="tab">Screenshot</a></li>
							@endif
							<li><a href="#test-{{ $id }}-errordata" data-toggle="tab">Error Data</a></li>
							@if($result["error"]->getPrevious() and $result["error"]->getPrevious() instanceof WebDriverException)
								<li><a href="#test-{{ $id }}-webdrivermessage" data-toggle="tab">Webdriver Message</a></li>
								<li><a href="#test-{{ $id }}-webdriverscreenshot" data-toggle="tab">Webdriver Screenshot</a></li>
							@endif
						@endif
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="test-{{ $id }}-log">
							<pre>{{ $result["log"] }}</pre>
						</div>
						@if(!$result["success"])
							@if(isset($result["error"]->data["screenshot"]))
								<div class="tab-pane fade" id="test-{{ $id }}-screenshot">
									<img src="data:image/png;base64,{{ $result["error"]->data["screenshot"] }}" />
								</div>
							@endif
							<div class="tab-pane fade" id="test-{{ $id }}-errordata">
								<pre>{{ var_dump(array_except($result["error"]->data, array("screenshot"))) }}</pre>
							</div>
							@if($result["error"]->getPrevious() and $result["error"]->getPrevious() instanceof WebDriverException)
								<div class="tab-pane fade" id="test-{{ $id }}-webdrivermessage">
									{{{ $result["error"]->getPrevious()->getResults()["value"]["message"] }}}
								</div>
								<div class="tab-pane fade" id="test-{{ $id }}-webdriverscreenshot">
									<img src="data:image/png;base64,{{ $result["error"]->getPrevious()->getResults()["value"]["screen"] }}" />
								</div>
							@endif
						@endif
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php /*
<h1>Results!</h1>
@foreach($results as $id => $result)
	<h2>{{{ $tests[$id]["name"] }}}</h2>
	@if($result["success"])
		<p>Test success!</p>
		<pre>{{ $result["log"] }}</pre>
	@else
		<p>Test failed</p>
		<pre>{{ $result["log"] }}</pre>
		<h3>{{ $result["error"]->getMessage() }}</h3>
		@if(isset($result["error"]->data["screenshot"]))
			<p>Screenshot:</p>
			<img src="data:image/png;base64,{{ $exception_results["value"]["screen"] }}" />
		@endif
		<pre>{{ var_dump(array_except($result["error"]->data, array("screenshot"))) }}</pre>
		@if($result["error"]->getPrevious())
			@if($result["error"]->getPrevious() instanceof WebDriverException)
				<?php $exception_results = $result["error"]->getPrevious()->getResults() ?>
				<h3>{{ $exception_results["state"] }}</h3>
				<p>Message:</p>
				{{ $exception_results["value"]["message"] }}
				<p>Screenshot:</p>
				<img src="data:image/png;base64,{{ $exception_results["value"]["screen"] }}" />
			@else
				<h3>{{ $result["error"]->getPrevious()->getMessage() }}</h3>
			@endif
		@endif
	@endif
@endforeach
*/