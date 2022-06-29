/**
 * External dependencies
 */
import * as React from 'react';
// import ReactDOM from 'react-dom';
// @ts-ignore
const { render } = wp.element;
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../../gc-lists/resources/js/src/common/Spinner';
// import { Success } from './components';
const Success = React.lazy(() => import("./components/Notice"));
/**
 * Internal dependencies
 */


const App = () => {
	return (
		<div>
			<h1>{__('Huzzah', 'cds-js-test')}</h1>
			<React.Suspense fallback={null}>
				<Success message={__('Success message', 'cds-js-test')} />
			</React.Suspense>
		</div>
	);
};



	const container = document.getElementById('app')!;



		render(
				<App />
			, container);


