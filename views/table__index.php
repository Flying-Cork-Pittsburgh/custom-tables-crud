<div class="wrap">
	<h1 class="wp-heading-inline"><?= $vData['pageTitle'] ?></h1>
	<?php /*
		<a href="http://budio.test/wp-admin/post-new.php?post_type=producers" class="page-title-action">Dodaj nowego</a>
	*/ ?>
	<hr class="wp-header-end">
	<?php /*
		<h2 class="screen-reader-text">Filtrowanie listy wpisów</h2>
		<ul class="subsubsub">
			<li class="all"><a href="edit.php?post_type=producers">Wszystkie <span class="count">(7)</span></a> |</li>
			<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=producers">Opublikowane <span class="count">(7)</span></a></li>
		</ul>
	*/ ?>
	<form id="posts-filter" method="get">
		<?php /*
			<p class="search-box">
				<label class="screen-reader-text" for="post-search-input">Szukaj:</label>
				<input type="search" id="post-search-input" name="s" value="">
				<input type="submit" id="search-submit" class="button" value="Szukaj">
			</p>
			<input type="hidden" name="post_status" class="post_status_page" value="all">
			<input type="hidden" name="post_type" class="post_type_page" value="producers">
			<input type="hidden" id="_wpnonce" name="_wpnonce" value="778b09191e"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit.php?post_type=producers&amp;mode=list">
		*/ ?>

		<div class="tablenav top">
			<?php
				/*
				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-top" class="screen-reader-text">Wybierz działanie na wielu</label>
					<select name="action" id="bulk-action-selector-top">
						<option value="-1">Masowe działania</option>
						<option value="edit" class="hide-if-no-js">Edytuj</option>
						<option value="trash">Przenieś do kosza</option>
						<option value="duplicate_post_clone">Clone</option>
					</select>
					<input type="submit" id="doaction" class="button action" value="Zastosuj">
				</div>
				<div class="alignleft actions">
					<label for="filter-by-date" class="screen-reader-text">Filtruj po dacie</label>
					<select name="m" id="filter-by-date" class="">
						<option selected="selected" value="0">Wszystkie daty</option>
						<option value="201809">Wrzesień 2018</option>
						<option value="201808">Sierpień 2018</option>
					</select>
					<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Przefiltruj">
				</div>
				*/


				?>

			<div class="alignleft actions">
				<?= $vData['filters']; ?>
				<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Przefiltruj">
				<input type="hidden" name="page" value="<?= $_GET['page'] ?? '' ?>">
				<input type="hidden" name="paged" value="<?= $_GET['paged'] ?? '' ?>">
			</div>

			<div class="tablenav-pages">
				<span class="displaying-num"><?= $paginator->totalItems() ?> pozycji</span>
				<span class="pagination-links">
					<?= $paginator->pagination() ?>
				</span>
			</div>
			<?php /*<br class="clear">
			<h2 class="screen-reader-text">Nawigacja listy wpisów</h2>
			<div class="tablenav-pages">
				<span class="displaying-num">7 pozycji</span>
				<span class="pagination-links">
					<span class="tablenav-pages-navspan" aria-hidden="true">«</span>
					<span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
					<span class="paging-input">
						<label for="current-page-selector" class="screen-reader-text">Bieżąca strona</label>
						<input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging">
						<span class="tablenav-paging-text"> z <span class="total-pages">2</span></span>
					</span>
					<a class="next-page" href="http://budio.test/wp-admin/edit.php?post_type=producers&amp;mode=list&amp;paged=2">
						<span class="screen-reader-text">Następna strona</span>
						<span aria-hidden="true">›</span>
					</a>
					<span class="tablenav-pages-navspan" aria-hidden="true">»</span>
				</span>
			</div>*/ ?>
			<br class="clear">
		</div>


		<h2 class="screen-reader-text">Lista wpisów</h2>
		<table class="wp-list-table widefat fixeddd striped posts">
			<thead>
				<tr>
					<?php /*
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Wybierz wszystko</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
							<a href="http://budio.test/wp-admin/edit.php?post_type=producers&amp;mode=list&amp;orderby=title&amp;order=asc"><span>Tytuł</span>
							<span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="date" class="manage-column column-date sortable asc">
							<a href="http://budio.test/wp-admin/edit.php?post_type=producers&amp;mode=list&amp;orderby=date&amp;order=desc"><span>Data</span>
							<span class="sorting-indicator"></span></a>
						</th>
					*/ ?>

					<?php foreach ($vData['tableFields'] as $key => $val): ?>
						<th scope="col" id="column_<?= $key ?>" class="manage-column column-<?= $key ?>">
							<?= $val['title'] ?>
						</th>
					<?php endforeach ?>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php /*
					<tr id="post-<?= $table ?>" class="iedit author-other level-0 status-publish has-post-thumbnail hentry">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-384">Wybierz Caparol</label>
							<input id="cb-select-384" type="checkbox" name="post[]" value="384">
							<div class="locked-indicator">
								<span class="locked-indicator-icon" aria-hidden="true"></span>
								<span class="screen-reader-text">“Caparol” jest zablokowany</span>
							</div>
						</th>
						<td class="title column-title has-row-actions column-primary page-title" data-colname="Tytuł">
							<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
							<strong><a class="row-title" href="http://budio.test/wp-admin/post.php?post=384&amp;action=edit" aria-label="„Caparol” (Edycja)">Caparol</a></strong>
							<div class="hidden" id="inline_384">
								<div class="post_title">Caparol</div>
								<div class="post_name">caparol</div>
								<div class="post_author">1</div>
								<div class="comment_status">closed</div>
								<div class="ping_status">closed</div>
								<div class="_status">publish</div>
								<div class="jj">17</div>
								<div class="mm">09</div>
								<div class="aa">2018</div>
								<div class="hh">10</div>
								<div class="mn">56</div>
								<div class="ss">43</div>
								<div class="post_password"></div>
								<div class="page_template">default</div>
								<div class="sticky"></div>
							</div>
							<div class="row-actions"><span class="edit"><a href="http://budio.test/wp-admin/post.php?post=384&amp;action=edit" aria-label="Edytuj „Caparol”" data-hasqtip="0">Edytuj</a> | </span><span class="inline hide-if-no-js"><a href="#" class="editinline" aria-label="Szybka edycja „Caparol”" data-hasqtip="1">Szybka&nbsp;edycja</a> | </span><span class="trash"><a href="http://budio.test/wp-admin/post.php?post=384&amp;action=trash&amp;_wpnonce=5a2d55f092" class="submitdelete" aria-label="Przenieś „Caparol” do kosza" data-hasqtip="2">Do kosza</a> | </span><span class="view"><a href="http://budio.test/producenci/caparol/" rel="bookmark" aria-label="Zobacz „Caparol”" data-hasqtip="3">Zobacz</a> | </span><span class="clone"><a href="http://budio.test/wp-admin/admin.php?action=duplicate_post_save_as_new_post&amp;post=384&amp;_wpnonce=24638f6bc0" data-hasqtip="4" oldtitle="Clone this item" title="">Clone</a> | </span><span class="edit_as_new_draft"><a href="http://budio.test/wp-admin/admin.php?action=duplicate_post_save_as_new_post_draft&amp;post=384&amp;_wpnonce=24638f6bc0" data-hasqtip="5" oldtitle="Copy to a new draft" title="">New Draft</a></span></div>
							<button type="button" class="toggle-row"><span class="screen-reader-text">Pokaż więcej szczegółów</span></button>
						</td>
						<td class="date column-date" data-colname="Data">Opublikowany<br><abbr title="2018-09-17 10:56:43">17.09.2018</abbr></td>
					</tr>
				*/ ?>

				<?php foreach ($items as $item): ?>
					<tr id="post-<?= key($item) ?>" class="iedit author-other level-0 status-publish has-post-thumbnail hentry" data-rowwrapper>
						<?php foreach ($item as $ckey => $cval): ?>
							<td class="date column-<?= $ckey ?>" data-colname="<?= $ckey ?>" contenteditable="<?= $editable_fields[$ckey] ? 'true' : 'false' ?>" <?= $editable_fields[$ckey] ? ' data-rowid="'.$item['id'].'" ' : '' ?>>
								<?= $cval ?>
							</td>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<?php foreach ($vData['tableFields'] as $key => $val): ?>
						<th scope="col" id="column_<?= $key ?>" class="manage-column column-<?= $key ?>">
							<?= $val['title'] ?>
						</th>
					<?php endforeach ?>
				</tr>
			</tfoot>
		</table>
		<?php /*
			<div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-bottom" class="screen-reader-text">Wybierz działanie na wielu</label>
					<select name="action2" id="bulk-action-selector-bottom">
						<option value="-1">Masowe działania</option>
						<option value="edit" class="hide-if-no-js">Edytuj</option>
						<option value="trash">Przenieś do kosza</option>
						<option value="duplicate_post_clone">Clone</option>
					</select>
					<input type="submit" id="doaction2" class="button action" value="Zastosuj">
				</div>
				<div class="alignleft actions">
				</div>
				<div class="tablenav-pages"><span class="displaying-num">7 pozycji</span>
					<span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
					<span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
					<span class="screen-reader-text">Bieżąca strona</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 z <span class="total-pages">2</span></span></span>
					<a class="next-page" href="http://budio.test/wp-admin/edit.php?post_type=producers&amp;mode=list&amp;paged=2"><span class="screen-reader-text">Następna strona</span><span aria-hidden="true">›</span></a>
					<span class="tablenav-pages-navspan" aria-hidden="true">»</span></span>
				</div>
				<br class="clear">
			</div>
		*/ ?>
	</form>
	<form method="get">
		<table style="display: none">
			<tbody id="inlineedit">
				<tr id="inline-edit" class="inline-edit-row inline-edit-row-page quick-edit-row quick-edit-row-page inline-edit-producers" style="display: none">
					<td colspan="4" class="colspanchange">
						<fieldset class="inline-edit-col-left">
							<legend class="inline-edit-legend">Szybka edycja</legend>
							<div class="inline-edit-col">
								<label>
								<span class="title">Tytuł</span>
								<span class="input-text-wrap"><input type="text" name="post_title" class="ptitle" value=""></span>
								</label>
								<label>
								<span class="title">Upr. nazwa</span>
								<span class="input-text-wrap"><input type="text" name="post_name" value=""></span>
								</label>
								<fieldset class="inline-edit-date">
									<legend><span class="title">Data</span></legend>
									<div class="timestamp-wrap">
										<label><span class="screen-reader-text">Dzień</span><input type="text" name="jj" value="17" size="2" maxlength="2" autocomplete="off"></label>-
										<label>
											<span class="screen-reader-text">Miesiąc</span>
											<select name="mm">
												<option value="01" data-text="Sty">01</option>
												<option value="02" data-text="Lut">02</option>
												<option value="03" data-text="Mar">03</option>
												<option value="04" data-text="Kwi">04</option>
												<option value="05" data-text="Maj">05</option>
												<option value="06" data-text="Cze">06</option>
												<option value="07" data-text="Lip">07</option>
												<option value="08" data-text="Sie">08</option>
												<option value="09" data-text="Wrz" selected="selected">09</option>
												<option value="10" data-text="Paź">10</option>
												<option value="11" data-text="Lis">11</option>
												<option value="12" data-text="Gru">12</option>
											</select>
										</label>
										-<label><span class="screen-reader-text">Rok</span><input type="text" name="aa" value="2018" size="4" maxlength="4" autocomplete="off"></label> o <label><span class="screen-reader-text">Godzina</span><input type="text" name="hh" value="10" size="2" maxlength="2" autocomplete="off"></label>:<label><span class="screen-reader-text">Minuta</span><input type="text" name="mn" value="56" size="2" maxlength="2" autocomplete="off"></label>
									</div>
									<input type="hidden" id="ss" name="ss" value="43">
								</fieldset>
								<br class="clear">
								<div class="inline-edit-group wp-clearfix">
									<label class="alignleft">
										<span class="title">Hasło</span>
										<span class="input-text-wrap"><input type="text" name="post_password" class="inline-edit-password-input" value=""></span>
									</label>
									<em class="alignleft inline-edit-or">–LUB–</em>
									<label class="alignleft inline-edit-private">
									<input type="checkbox" name="keep_private" value="private">
									<span class="checkbox-title">Prywatne</span>
									</label>
								</div>
							</div>
						</fieldset>
						<fieldset class="inline-edit-col-right">
							<div class="inline-edit-col">
								<div class="inline-edit-group wp-clearfix">
									<label class="inline-edit-status alignleft">
										<span class="title">Stan</span>
										<select name="_status">
											<option value="publish">Opublikowany</option>
											<option value="future">Zaplanowano</option>
											<option value="pending">Oczekuje na przegląd</option>
											<option value="draft">Szkic</option>
										</select>
									</label>
								</div>
							</div>
						</fieldset>
						<div class="submit inline-edit-save">
							<button type="button" class="button cancel alignleft">Anuluj</button>
							<input type="hidden" id="_inline_edit" name="_inline_edit" value="00c9f05201">				<button type="button" class="button button-primary save alignright">Zaktualizuj</button>
							<span class="spinner"></span>
							<input type="hidden" name="post_view" value="list">
							<input type="hidden" name="screen" value="edit-producers">
							<input type="hidden" name="post_author" value="">
							<br class="clear">
							<div class="notice notice-error notice-alt inline hidden">
								<p class="error"></p>
							</div>
						</div>
					</td>
				</tr>
				<tr id="bulk-edit" class="inline-edit-row inline-edit-row-page bulk-edit-row bulk-edit-row-page bulk-edit-producers" style="display: none">
					<td colspan="4" class="colspanchange">
						<fieldset class="inline-edit-col-left">
							<legend class="inline-edit-legend">Masowa edycja</legend>
							<div class="inline-edit-col">
								<div id="bulk-title-div">
									<div id="bulk-titles"></div>
								</div>
							</div>
						</fieldset>
						<fieldset class="inline-edit-col-right">
							<div class="inline-edit-col">
								<div class="inline-edit-group wp-clearfix">
									<label class="inline-edit-status alignleft">
										<span class="title">Stan</span>
										<select name="_status">
											<option value="-1">— bez zmian —</option>
											<option value="publish">Opublikowany</option>
											<option value="private">Prywatne</option>
											<option value="pending">Oczekuje na przegląd</option>
											<option value="draft">Szkic</option>
										</select>
									</label>
								</div>
							</div>
						</fieldset>
						<div class="submit inline-edit-save">
							<button type="button" class="button cancel alignleft">Anuluj</button>
							<input type="submit" name="bulk_edit" id="bulk_edit" class="button button-primary alignright" value="Zaktualizuj">			<input type="hidden" name="post_view" value="list">
							<input type="hidden" name="screen" value="edit-producers">
							<br class="clear">
							<div class="notice notice-error notice-alt inline hidden">
								<p class="error"></p>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<div id="ajax-response"></div>
	<br class="clear">
</div>
