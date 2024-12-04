import React from 'react';

const WizardGuide = ( { state } ) => {
	const { wizard } = state;

	const steps = () => {
		let badgeNumber = 0;

		const lastStep = Object.keys( wizard.steps ).length - 1 === wizard.step;

		let result = Object.keys( wizard.steps ).map( ( step, index ) => {
			if ( ! wizard.steps[ step ].enabled ) {
				return <></>;
			}
			const classes = [
				'wizard__guide',
				! lastStep && wizard.steps[ step ].step == wizard?.step ? 'wizard__guide--active text--primary' : false,
				wizard.steps[ step ].valid ? 'wizard__guide--valid' : 'wizard__guide--invalid',
				wizard.steps[ step ].step < wizard?.step ? 'wizard__guide--done' : 'wizard__guide--pending',
				lastStep && wizard.steps[ step ].step == wizard?.step && state?.response?.error
					? 'wizard__guide--error'
					: false,
				wizard.step == 3 && state?.response?.booking?.booking_id ? 'wizard__guide--done' : false,
			]
				.filter( Boolean )
				.join( ' ' );

			const badgeContent = () => {
				if ( wizard.step == 3 && state?.response?.booking?.booking_id ) {
					return <i className="material-icons material-symbols-outlined">done</i>;
				}
				if ( wizard.steps[ step ].step >= wizard.step ) {
					return <span>{ badgeNumber }</span>;
				}
				if ( wizard.steps[ step ].valid )
					return <i className="material-icons material-symbols-outlined">done</i>;
				return <i className="material-icons material-symbols-outlined">close</i>;
			};

			badgeNumber++;

			return (
				<div key={ index } className={ classes }>
					<div className="wizard__badge">{ badgeContent() }</div>
					<span key={ index }>{ wizard.steps[ step ].label }</span>
				</div>
			);
		} );

		return result;
	};

	return <div className="wizard__guides">{ steps() }</div>;
};

export default WizardGuide;
