/**
 * Wordpress dependencies
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { Button, TextControl, TextareaControl  } from '@wordpress/components';
import { __ } from '@wordpress/i18n'; 
import { useEffect, useState } from '@wordpress/element';


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
			placeholder,
			label,
			fieldid,
			options,
		},
		setAttributes
		
	} = props;

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

	console.log(placeholder)

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
			<div className='ctx:event-field__select'>
				<fieldset>
					
					{ options.map((option, index) => {
						return (
							<div>
								<input type="radio" name={index} value={index} checked={placeholder == index} onChange={() => {setAttributes({placeholder: index})}}/>
								<label>{option}</label>
							</div>
						)
					}) }
				</fieldset>
			
			</div>
			
		</div>
	);

}


export default edit;