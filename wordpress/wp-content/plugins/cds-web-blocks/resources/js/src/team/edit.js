import { useBlockProps } from '@wordpress/block-editor';
import TextControl from '../components/TextControl';
import { __ } from '@wordpress/i18n';

const Edit = ({ attributes, setAttributes }) => {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<p>{__('Team Member', 'cds-web')}</p>
			<TextControl label={__('Name', 'cds-web')} metaKey="cds_web_team_member_name" />
			<TextControl label={__('Title English', 'cds-web')} metaKey="cds_web_team_member_title_en" />
			<TextControl label={__('Title French', 'cds-web')} metaKey="cds_web_team_member_title_fr" />
			<p>{__('Contact Information', 'cds-web')}</p>
			<TextControl label={__('Email', 'cds-web')} metaKey="cds_web_team_member_email" />
			<TextControl label={__('Twitter', 'cds-web')} metaKey="cds_web_team_member_twitter" />
			<TextControl label={__('Github', 'cds-web')} metaKey="cds_web_team_member_github" />
			<TextControl label={__('LinkedIn', 'cds-web')} metaKey="cds_web_team_member_linkedin" />
		</div>
	);
};

export default Edit;
