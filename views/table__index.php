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
		<?php if ($searchEnabled): ?>
			<p class="search-box">
				<label class="screen-reader-text" for="post-search-input">Szukaj:</label>
				<input type="search" id="post-search-input" name="s" value="<?= $_GET['s'] ?? '' ?>">
				<input type="submit" id="search-submit" class="button" value="Szukaj">
			</p>
		<?php endif ?>
		<?php /*
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
			*/ ?>

			<div class="alignleft actions">
				<?php if ($filtersEnabled): ?>
					<?= $vData['filters']; ?>
					<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Przefiltruj">
				<?php endif ?>
				<input type="hidden" name="page" value="<?= $_GET['page'] ?? '' ?>">
				<input type="hidden" name="paged" value="<?= $_GET['paged'] ?? '' ?>">
			</div>

			<div class="tablenav-pages">
				<span class="displaying-num"><?= $paginator->totalItems() ?> pozycji</span>
				<span class="pagination-links">
					<?= $paginator->pagination() ?>
				</span>
			</div>

			<?php /*
				<br class="clear">
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
				</div>
			*/ ?>

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
						<th scope="col" id="column_<?= $key ?>"
							class="manage-column column-<?= $key ?>
								<?= $vData['templateHelper']->getOrderBeingChosenClass($key, 'th') ?>
								<?= $val['orderable'] ? 'sortable' : '' ?>
								<?= (!empty($_GET['order']) && strtolower($_GET['order']) == 'asc') ? 'asc' : 'desc' ?>
							">
							<?php if ($val['orderable']): ?>
								<a href="<?= $vData['templateHelper']->getNewOrderLink($key) ?>"
									class="<?= $vData['templateHelper']->getOrderBeingChosenClass($key) ?>">
									<span><?= $val['title'] ?></span>
									<?= $vData['templateHelper']->getOrderIndicator($key) ?>
								</a>
							<?php else: ?>
								<?= $val['title'] ?>
							<?php endif ?>
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
							<td class="date column-<?= $ckey ?>" data-colname="<?= $ckey ?>" contenteditable="<?= $vData['editableFields'][$ckey] ? 'true' : 'false' ?>" <?= $vData['editableFields'][$ckey] ? ' data-rowid="'.$item['id'].'" ' : '' ?>>
								<?php if (!empty($cval['link'])): ?>
									<a href="<?= $cval['link'] ?>" target="new"><?= is_array($cval) ? $cval['value'] : $cval ?></a>
								<?php else: ?>
									<?= is_array($cval) ? $cval['value'] : $cval ?>
								<?php endif ?>
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
	</form>
	<div id="ajax-response"></div>
	<br class="clear">
</div>
