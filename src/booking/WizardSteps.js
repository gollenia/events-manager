import React, { Children } from 'react';

const WizardSteps = ( props ) => {
	const { children, onNext, onPrev, state, dispatch } = props;

	const count = Children.count( children );

	const currentStep = state.wizard.step;

	const currentChild = Children.toArray( children )[ currentStep ];

	const nextStep = () => {
		if ( ! currentChild.props.valid ) return;

		dispatch( { type: 'INCREMENT_WIZARD' } );
		onNext( currentStep + 1, count );
	};

	const prevStep = () => {
		dispatch( { type: 'DECREMENT_WIZARD' } );
		onPrev( currentStep );
	};

	return (
		<div className="wizard">
			<div className="wizard__steps">
				{ Children.map( children, ( child, index ) => {
					return React.cloneElement( child, { index, currentStep } );
				} ) }
			</div>
		</div>
	);
};

export default WizardSteps;
