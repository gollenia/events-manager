import React from 'react';

const WizardStep = ( props ) => {
	const { children, isActive, index, currentStep, valid, nextButtonLabel, invalidMessage } = props;

	const style = {
		left: 100 * index + '%',
		transform: `translateX(-${ 100 * currentStep }%)`,
	};

	const classes = [
		'wizard__step',
		isActive ? 'wizard__step--active' : '',
		valid ? 'wizard__step--valid' : 'wizard__step--invalid',
		currentStep > index ? 'wizard__step--done' : 'wizard__step--pending',
	]
		.filter( Boolean )
		.join( ' ' );

	console.log( props );

	return (
		<div style={ style } className={ classes }>
			{ children }
		</div>
	);
};

export default WizardStep;
