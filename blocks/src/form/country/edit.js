/**
 * Wordpress dependencies
 */
import { ToggleControl, RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n'; 
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { store as coreStore } from '@wordpress/core-data';
import { select } from "@wordpress/data";



/**
 * Internal dependencies
 */
import Inspector from './inspector.js';


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = (props) => {

	const {
		attributes: {
			width,
			required,
			pattern,
			label,
			fieldid,
			help,
			error
		},
		setAttributes
		
	} = props;

	const countries = () => {
		
	}

	const validFieldId = () => {
		const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
		return (validPattern.test(fieldid));
	}

	const setFieldId = (value) => {
		value = value.toLowerCase()
		value = value.replace(/\s/g, '-')
		setAttributes({ fieldid: value.toLowerCase() });
	}

	const blockProps = useBlockProps({
		className: [
			"ctx:event-field",
			"ctx:event-field--" + width,
			validFieldId() == false ? "ctx:event-field--error" : ""
		].filter(Boolean).join(" ")
	});

	

	return (
		<div {...blockProps}>
			<Inspector {...props} />
			
			<div className="ctx:event-field__caption">
			<div>
			<RichText
				tagName="span"
				className="ctx:event-details__label"
				value={label}
				placeholder={__("Label", "events")}
				onChange={(value) => setAttributes({ label: value })}
			/>
			<span>{(required ? "*": "")}</span><br/>
			<span className="ctx:event-field__label">{__("Label for the field", "events")}</span>
			</div>
			
			<div className="ctx:event-field__name">
			<RichText
				tagName="p"
				className="ctx:event-details__label"
				value={fieldid}
				
				placeholder={__("Slug", "events")}
				onChange={(value) => setFieldId( value )}
			/>
			{ validFieldId() == false && <span className="ctx:event-field__error-message">{__("Please type in a unique itentifier for the field", "events")}</span> }
			{ validFieldId() && <span className="ctx:event-field__label">{__("Unique identifier", "events")}</span> }
			</div>

			</div>
			<select>
			{ Object.keys(window.eventBlocksLocalization?.countries).map((country) => {
				return <option value={country}>{window.eventBlocksLocalization?.countries[country]}</option>
			})}
			</select>
		</div>
	);

}


export default edit;