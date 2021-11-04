import * as React from 'react';
import { renderToString } from '@wordpress/element';
import { __ } from "@wordpress/i18n";

const Icon = () => {
	return (
		<div className="cds_interstitial-message">
			<SVGImage />
			<p className="preview-text">{__('Generating preview…')}</p>
		</div>
	)
}

const SVGImage = () => {
	return (
		<svg
			width={180}
			height={200}
			viewBox="0 0 38 43"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"

		>
			<path
				className="path"
				strokeWidth={2}
				d="M20.195 42l-.315-8.915c.024-.77.685-.77 1.638-.532l7.71 1.407-.764-2.367c-.315-.984-.607-1.253.237-2.127L37 22.416l-1.426-.716c-.74-.324-.424-.88-.267-1.546v-.11l1.347-5.106-4.01.85c-.691.128-1.213.232-1.56-.483l-1.03-2.366-3.883 4.182c-1.062 1.174-2.038.666-1.668-1.064l1.771-8.81-2.724 1.356c-.63.318-.952.373-1.347-.293L19.036 2l-3.142 6.414c-.291.556-.795.477-1.268.183l-2.985-1.73 1.93 9.55c.315 1.223-.692 1.78-1.402.93l-4.016-4.525-1.135 2.342c-.291.612-.606.88-1.74.612L1.211 14.9l1.584 5.167c.236.776.212 1.143-.237 1.492L1 22.52l8.427 6.952c.716.611.63 1.223.315 2.127l-.922 2.367 7.153-1.382c1.559-.324 2.22-.349 2.22.526L17.878 42z"
				stroke="#000"
			/>
		</svg>

	);
};

export const writeInterstitialMessage = () => {
	let markup = renderToString(<Icon />);
	markup += `
		<style>
			body {
				margin: 0;
			}
			.cds_interstitial-message {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				height: 100vh;
				width: 100vw;
			}

			.path {
				stroke-dasharray: 166.71;
				stroke-dashoffset: 166.71;
				animation: dash 3.1s linear alternate infinite;
			  }
			  
			@keyframes dash {
				from {
				  stroke-dashoffset: 166.71;
				}
				to {
				  stroke-dashoffset: 0;
				}
			}

			.cds_interstitial-message svg {
				width: 250px;
				height: 250px;
			}
			.preview-text {
				text-align: center;
				font-family: 'Noto Sans', sans-serif;
			}
		</style>
	`;

	return markup;
};

// note this filter gets added in index.tsx
/*
wp.hooks.addFilter(
	'editor.PostPreview.interstitialMarkup',
	'my-plugin/custom-preview-message',
	() => window.CDS.writeInterstitialMessage()
);
*/
