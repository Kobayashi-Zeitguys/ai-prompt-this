import { registerBlockType } from '@wordpress/blocks';
import { SVG, Path } from '@wordpress/primitives';
import './style.scss';
import Edit from './edit';
import save from './save';
import metadata from './block.json';

const icon = (
	<SVG width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
		<Path d="M6.25 4.25c0.95 1.19 2.11 2.19 3.5 3-1.46 0.56-2.46 1.56-3 3-0.56-1.46-1.56-2.46-3-3a16.18 16.18 0 0 1 2.25-1.5c0.24-0.47 0.33-0.97 0.25-1.5z" />
		<Path d="M15.25 4.25c0.44-0.07 0.77 0.1 1 0.5 0.37 1.94 1.53 3.11 3.5 3.5-0.71 0.65-1.54 1.07-2.5 1.25a30.4 30.4 0 0 0-1.5 2.75c-0.65-0.71-1.07-1.54-1.25-2.5a30.4 30.4 0 0 0-2.75-1.5 10.24 10.24 0 0 1 2.75-1.5c0.5-0.76 0.75-1.6 0.75-2.5z" opacity=".786" />
		<Path d="M10.25 10.25c0.44-0.07 0.77 0.1 1 0.5 0.49 2.49 1.99 3.99 4.5 4.5a8.77 8.77 0 0 1-3 1.5 18.16 18.16 0 0 0-2 3.5 8.77 8.77 0 0 1-1.5-3 18.16 18.16 0 0 0-3.5-2l3.5-2c0.62-0.91 0.96-1.91 1-3z" opacity=".809" />
	</SVG>
);

registerBlockType( metadata.name, {
	icon,
	edit: Edit,
	save,
} );
