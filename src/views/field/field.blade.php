                    <form class="form-horizontal" method="POST" action="{{ empty($id) ? route('segnalazioni.store') : route('segnalazioni.update', [ 'segnalazione' => $id ]) }}">
                        {{ csrf_field() }}

						<div class="form-group">
							<label class="col-md-4 control-label">Tipo di articolo</label>

							<div class="col-md-6">
								<div class="form-check">
								  <label class="form-check-label" for="tipo_segnalazione">
								  <input class="form-check-input" type="radio" value="segnalazione" id="tipo_segnalazione" name="tipo"{{ old('tipo', $tipo) == 'segnalazione' ? ' checked' : '' }}>
									Semplice segnalazione
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label" for="tipo_articolo">
								  <input class="form-check-input" type="radio" value="articolo" id="tipo_articolo" name="tipo"{{ old('tipo', $tipo) == 'articolo' ? ' checked' : '' }}>
									Recensione o approfondimento
								  </label>
								</div>

                                @if ($errors->has('tipo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>

					   <div class="form-group{{ $errors->has('isbn') ? ' has-error' : '' }}">
                            <label for="isbn" class="col-md-4 control-label">Libro di cui si parla</label>

                            <div class="col-md-6">
                                <input id="isbn" type="text" class="form-control" name="isbn" value="{{ old('isbn', $isbn) }}" required>

                                @if ($errors->has('isbn'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('isbn') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">Indirizzo (URL) dell'articolo</label>

                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control" name="url" value="{{ old('url', $url) }}" required placeholder="http://">

                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('testata') ? ' has-error' : '' }}">
                            <label for="testata" class="col-md-4 control-label">Testata / Titolo del blog</label>

                            <div class="col-md-6">
                                <input id="testata" type="text" class="form-control" name="testata" value="{{ old('testata', $testata) }}" required>

                                @if ($errors->has('testata'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('testata') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('autore') ? ' has-error' : '' }}">
                            <label for="autore" class="col-md-4 control-label">Autore dell'articolo</label>

                            <div class="col-md-6">
                                <input id="autore" type="text" class="form-control" name="autore" value="{{ old('autore', $autore) }}">

                                @if ($errors->has('autore'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('autore') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('titolo_articolo') ? ' has-error' : '' }}">
                            <label for="titolo_articolo" class="col-md-4 control-label">Titolo dell'articolo</label>

                            <div class="col-md-6">
                                <input id="titolo_articolo" type="text" class="form-control" name="titolo_articolo" value="{{ old('titolo_articolo', $titolo_articolo) }}">
								<small class="form-text text-muted">Se diverso dal titolo del libro</small>

                                @if ($errors->has('titolo_articolo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('titolo_articolo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('stralcio') ? ' has-error' : '' }}">
                            <label for="stralcio" class="col-md-4 control-label">Breve stralcio dell'articolo</label>

                            <div class="col-md-6">
                                <textarea id="stralcio" class="form-control" name="stralcio" rows="5">{{ old('stralcio', $stralcio) }}</textarea>
								<small class="form-text text-muted">Una frase particolarmente significativa, che potrebbe essere usata come blurb in edizioni successive.</small>

                                @if ($errors->has('stralcio'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stralcio') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Salva
                                </button>
                            </div>
                        </div>
                    </form>
