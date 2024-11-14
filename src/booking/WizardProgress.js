import React from 'react';

const WizardProgress = ( { index, count, valid, currentStep, label } ) => {
	console.log( { index, count, valid, currentStep, label } );
	const lastStep = count - 1 === currentStep;
	const classes = [
		'wizard__progress',
		lastStep ? 'wizard__guide--active text--primary' : false,
		valid ? 'wizard__guide--valid' : 'wizard__guide--invalid',
		currentStep > index ? 'wizard__guide--done' : 'wizard__guide--pending',
	]
		.filter( Boolean )
		.join( ' ' );

	return (
		<div key={ index } className={ classes }>
			<div className="wizard__badge">{ index }</div>
			<span key={ index }>{ label }</span>
		</div>
	);
};

export default WizardProgress;
