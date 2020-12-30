<div class="devkit-code-field">
	<div class="editor-instructions">

		<p>Supports all common scss, including nesting, variables, etc<p>

		<p>2 variables are built in, and represent global beaver builder settings<br/>
		- $medium-breakpoint</br>
		- $responsive-breakpoint</p>

	</div>

	<div class="devkit-scss-editor-header">
		<span class="gutter">0</span><span class="code-header">{{data.field.prefix}} {</span>
	</div>

	<# var editorId = 'becode' + new Date().getTime() + '_' + data.name; #>

	<textarea class="devkit-code-editor"
	rows="10"
	id="{{editorId}}"
	name="{{data.name}}"
	data-editor="sass"
	<# if ( data.field.className ) { #>class="{{data.field.className}}" <# } #>
	<# if ( data.field.placeholder ) { #>placeholder="{{data.field.placeholder}}" <# } #>
	<# if ( data.field.rows ) { #>rows="{{data.field.rows}}" <# } #>
	>{{data.value}}</textarea>

	<div class="devkit-scss-editor-header">
		<span class="gutter">~</span><span class="code-header">}</span>
	</div>


</div>