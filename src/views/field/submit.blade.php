			<div class="col text-right">
				<span class="submit-buttons">
					@if(isset( $field->reset ))
					<button type="reset" class="btn btn-secondary float-left">
						{{ $field->reset }}
					</button>
					@endif
					@if(isset( $field->close ))
					<button type="button" class="btn btn-secondary float-left" data-action="leave-editor" data-mode="">
						{{ $field->close }}
					</button>
					@endif
					<button type="submit" class="btn btn-primary" data-action="save-form" data-mode="save-exit">
						{{ $field->label }}
					</button>
					@if(isset( $field->andContinue ))
					<button type="submit" class="btn btn-primary" data-action="save-form" data-mode="save-continue" name="_andContinue" value="1">
						{{ $field->andContinue }}
					</button>
					@endif
				</span>
			</div>
