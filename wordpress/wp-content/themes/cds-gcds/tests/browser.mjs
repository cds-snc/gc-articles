import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { join } from 'node:path';

const require = createRequire( join( process.cwd(), 'noop.js' ) );
const { chromium } = require( 'playwright' );
const base = new URL( process.argv[ 2 ] || 'http://localhost:8889' );
assert.equal(
	base.hostname,
	'localhost',
	'Use the local test fixtures, not a production site.'
);
const browser = await chromium.launch();
const tags = [
	'heading',
	'text',
	'link',
	'button',
	'card',
	'container',
	'grid',
	'grid-col',
	'details',
	'notice',
	'table',
	'sr-only',
	'icon',
	'pagination',
	'side-nav',
	'nav-group',
	'nav-link',
];
try {
	for ( const language of [ 'en', 'fr' ] ) {
		const page = await browser.newPage();
		const errors = [];
		page.on( 'pageerror', ( error ) => errors.push( error.message ) );
		await page.goto(
			new URL( `/cds-gcds-test-${ language }/`, base ).href,
			{ waitUntil: 'networkidle' }
		);
		await page.waitForFunction( () =>
			[ ...document.querySelectorAll( '*' ) ]
				.filter( ( element ) =>
					element.localName.startsWith( 'gcds-' )
				)
				.every( ( element ) =>
					element.classList.contains( 'hydrated' )
				)
		);
		assert.equal(
			await page.locator( 'html' ).getAttribute( 'lang' ),
			language
		);
		for ( const tag of tags ) {
			assert.ok(
				await page.locator( `article gcds-${ tag }` ).count(),
				`${ language }: missing ${ tag }`
			);
		}
		const other = language === 'en' ? 'fr' : 'en';
		assert.equal(
			await page
				.locator( 'gcds-header' )
				.locator( 'gcds-lang-toggle' )
				.getByRole( 'link' )
				.getAttribute( 'href' ),
			new URL( `/cds-gcds-test-${ other }/`, base ).href
		);
		const details = page.locator(
			'gcds-details[details-title="Sample details"]'
		);
		await details.getByRole( 'button' ).click();
		await details.getByRole( 'button', { expanded: true } ).waitFor();
		assert.equal(
			await details.getByRole( 'button' ).getAttribute( 'aria-expanded' ),
			'true'
		);

		const rich = page.locator( '[data-cds-table]' );
		const visible = rich.locator( 'tbody tr:not([hidden])' );
		assert.equal( await visible.count(), 10 );
		await rich.locator( '[data-next]' ).getByRole( 'button' ).click();
		assert.equal( await visible.count(), 2 );
		await rich.locator( '[data-previous]' ).getByRole( 'button' ).click();
		assert.equal( await visible.count(), 10 );
		await rich
			.locator( '[data-sort-column="1"]' )
			.getByRole( 'button' )
			.click();
		assert.equal( await visible.first().locator( 'td' ).last().innerText(), '1.25' );
		await rich
			.locator( '[data-sort-column="1"]' )
			.getByRole( 'button' )
			.click();
		assert.equal( await visible.first().locator( 'td' ).last().innerText(), '12' );
		assert.equal(
			await rich
				.locator( 'thead th' )
				.nth( 1 )
				.getAttribute( 'aria-sort' ),
			'descending'
		);
		await rich
			.locator( 'gcds-input' )
			.getByRole( 'searchbox' )
			.fill( 'Entry 2' );
		assert.equal( await visible.count(), 1 );
		assert.equal(
			await visible.getByRole( 'link' ).getAttribute( 'href' ),
			'#sample-section'
		);
		await rich.locator( 'gcds-input' ).getByRole( 'searchbox' ).fill( '' );
		await rich
			.locator( 'gcds-select' )
			.getByRole( 'combobox' )
			.selectOption( '0' );
		assert.equal( await visible.count(), 12 );
		await rich.locator( '[data-next]' ).getByRole( 'button', { disabled: true } ).waitFor();
		assert.equal(
			await rich
				.locator( '[data-next]' )
				.getByRole( 'button' )
				.isDisabled(),
			true
		);
		const desktopLeft = await page.locator( 'gcds-text' ).filter( { hasText: 'Left layout' } ).boundingBox();
		const desktopRight = await page.locator( 'gcds-text' ).filter( { hasText: 'Right layout' } ).boundingBox();
		assert.ok( desktopRight.x > desktopLeft.x, `${ language }: legacy columns must sit side by side on desktop` );
		await page.setViewportSize( { width: 375, height: 812 } );
		const left = await page.locator( 'gcds-text' ).filter( { hasText: 'Left layout' } ).boundingBox();
		const right = await page.locator( 'gcds-text' ).filter( { hasText: 'Right layout' } ).boundingBox();
		assert.ok( right.y > left.y, `${ language }: legacy columns must stack on mobile` );
		assert.equal(
			await page.evaluate(
				() => document.documentElement.scrollWidth <= window.innerWidth
			),
			true,
			`${ language }: page overflows on mobile`
		);
		assert.deepEqual( errors, [] );
		console.log(
			`${ language }: all 17 components, language link, details, rich-table filter/sort/pagination and mobile layout passed.`
		);
		await page.close();
	}
} finally {
	await browser.close();
}
