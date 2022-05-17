import React from 'react'

const Guide = ({state}) => {

	const { wizzard } = state;

	const steps = () => {
		let stepNumber = 0;

		let result = Object.keys(wizzard?.steps).map((step, index) => { 		
			
			if(!wizzard.steps[step].enabled) {
				console.log("Nonono")
				return <></>;
			}
			const classes = [
				"wizzard__guide",
				wizzard.steps[step].step == wizzard?.step ? 'wizzard__guide--active text--primary' : false,
				wizzard.steps[step].valid ? 'wizzard__guide--valid' : 'wizzard__guide--invalid',
				wizzard.steps[step].step < wizzard?.step ? 'wizzard__guide--done' : 'wizzard__guide--pending'
			].filter(Boolean).join(" ");

			const badgeContent = () => {
				if(wizzard.steps[step].step >= wizzard.step) {
					return <span>{stepNumber}</span>
				}
				if(wizzard.steps[step].valid) return <i className="material-icons">done</i>
				return <i className="material-icons">close</i>
			}

			stepNumber++;
			
			console.log("sdlfkjgkj")
			return (
				<div className={classes}>
					<div class="wizzard__badge">{badgeContent()}</div>
					<span key={index}>{wizzard.steps[step].label}</span>
				</div>
			);

			
		})	

		return result;
	}

	return (
		<div className='wizzard__guides'>{ steps() }</div>
	)
}

export default Guide