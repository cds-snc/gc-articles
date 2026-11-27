( function () {
	'use strict';

	document.querySelectorAll( '[data-cds-table]' ).forEach( ( wrapper ) => {
		const table = wrapper.querySelector( 'table' );
		const body = table.tBodies[ 0 ];
		const rows = Array.from( body.rows );
		const form = wrapper.querySelector( 'form' );
		const previous = form.querySelector( '[data-previous]' );
		const next = form.querySelector( '[data-next]' );
		const status = form.querySelector( '[data-status]' );
		const collator = new Intl.Collator( document.documentElement.lang, {
			numeric: true,
			sensitivity: 'base',
		} );
		let query = '';
		let size = 10;
		let currentPage = 1;
		let sortedColumn = -1;
		let direction = 1;

		function update() {
			const matching = rows.filter( ( row ) =>
				row.textContent
					.toLocaleLowerCase( document.documentElement.lang )
					.includes( query )
			);
			const pageSize = size || Math.max( matching.length, 1 );
			const pages = Math.max(
				1,
				Math.ceil( matching.length / pageSize )
			);
			currentPage = Math.max( 1, Math.min( currentPage, pages ) );
			const start = ( currentPage - 1 ) * pageSize;
			const visible = new Set(
				matching.slice( start, start + pageSize )
			);
			rows.forEach( ( row ) => {
				row.hidden = ! visible.has( row );
				body.appendChild( row );
			} );
			previous.toggleAttribute( 'disabled', currentPage === 1 );
			next.toggleAttribute( 'disabled', currentPage === pages );
			status.textContent = `${ status.dataset.label }: ${
				matching.length ? start + 1 : 0
			}-${ Math.min( start + pageSize, matching.length ) } / ${
				matching.length
			}`;
		}

		form.addEventListener( 'submit', ( event ) => event.preventDefault() );
		form.querySelector( 'gcds-input' ).addEventListener(
			'gcdsInput',
			( event ) => {
				query = event.detail.toLocaleLowerCase(
					document.documentElement.lang
				);
				currentPage = 1;
				update();
			}
		);
		form.querySelector( 'gcds-select' ).addEventListener(
			'gcdsChange',
			( event ) => {
				size = Number( event.detail );
				currentPage = 1;
				update();
			}
		);
		previous.addEventListener( 'gcdsClick', () => {
			currentPage--;
			update();
		} );
		next.addEventListener( 'gcdsClick', () => {
			currentPage++;
			update();
		} );
		wrapper
			.querySelectorAll( '[data-sort-column]' )
			.forEach( ( button ) => {
				button.addEventListener( 'gcdsClick', () => {
					const column = Number( button.dataset.sortColumn );
					direction = sortedColumn === column ? -direction : 1;
					sortedColumn = column;
					rows.sort( ( left, right ) => {
						const a = left.cells[ column ].textContent.trim();
						const b = right.cells[ column ].textContent.trim();
						const numeric =
							a !== '' &&
							b !== '' &&
							Number.isFinite( Number( a ) ) &&
							Number.isFinite( Number( b ) );
						return (
							direction *
							( numeric
								? Number( a ) - Number( b )
								: collator.compare( a, b ) )
						);
					} );
					table
						.querySelectorAll( 'thead th' )
						.forEach( ( header, index ) => {
							const order =
								direction === 1 ? 'ascending' : 'descending';
							header.setAttribute(
								'aria-sort',
								index === column ? order : 'none'
							);
						} );
					currentPage = 1;
					update();
				} );
			} );
		update();
	} );
} )();
