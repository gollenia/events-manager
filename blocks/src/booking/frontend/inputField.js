const React = require('react');

import PropTypes from "prop-types"

const InputField = (props) => {
    let InputTag

    const {type, name, label, required = false, pattern, defaultValue, options, selectHint, value, min, max, half} = props

	const getLabel = () => {
		const parser = new DOMParser();
		return parser.parseFromString(`<!doctype html><body>${label}`, 'text/html').body.textContent;

	}
	
	const createMarkup = (textString) => {
        return {__html: textString};
    }

	if (type === "html") {
		const content = createMarkup(value);

		if(!content.__html) return (<div>{value}</div>);
		return (<div className="help" dangerouslySetInnerHTML={content}></div>);
	}

    const handleChange = event => {
        props.onChange(event.target.value)
    }

    const handleOptionChange = (value) => {
        props.onChange(value)
    }

    const handleCheckboxChange = (event) => {  
        props.onChange(event.target.checked)
    }

    const selectOptions = () => {
        if (type !== "select") return []
        if (options.length === 0) return []
        
        if(!Array.isArray(options)) {
            const result = []
            Object.entries(options).forEach(entry => {
                const [key, label] = entry;
            
                result.push(<option selected={value == key} key={key} value={key}>{label}</option>)
            });
            return result;
        }

        return options.map((option, index) => {
            return (<option selected={value == option} key={index}>{option}</option>)
        })
    }

    const radioOptions = () => {

        if (type !== "radio") return []
        if (options.length === 0) return []
        return options.map((option, index) => {
            if (typeof option === 'object') {
                return(<div className="radio" key={option.key}>
                    <label htmlFor={option.key}>
                    <input onChange={() => {handleOptionChange(option)}} type="radio" name={`${name}[${option.key}]`} checked={option.name == value} />
                    {option.name}</label>
                </div>)
            }
            
            return (<div key={index}>
                    <label htmlFor={index}>
                    <input onChange={() => {handleOptionChange(option)}} type="radio" name={`${name}[${index}]`} checked={option == value} />
                    {option}</label>
            </div>)
        })
    }


    switch (type) {
        case "select":
            InputTag = (
                <div className={"select" + (half ? " select--half" : "")}>
                    <label>{getLabel()}</label>
                    <select onChange= {handleChange} name={name} required={required}>
                        { defaultValue && <option value="">{defaultValue}</option>}
                        { !defaultValue && <option value="">{selectHint}</option>}
                        { selectOptions() }
                    </select>
                </div>
            )
            break;
        case "radio":
            InputTag = (
                <div className="radio">
                    <label>{getLabel()}</label>
                    <fieldset className="radio">
                        { radioOptions() }
                    </fieldset>
                </div>
            )
            break;
        case "checkbox":
            InputTag = (
                <div className="checkbox">
                    <label>
                    <input onChange={(event) => {handleCheckboxChange(event)}} type="checkbox" name={name} required={required} />
                    <span dangerouslySetInnerHTML={createMarkup(label)}></span>
                    </label>
                </div>
            )
            break;
        case "date":
            InputTag = (
                <div className={"input" + (half ? " input--half" : "")}>
                    <label>{getLabel()}</label>
                    <input onChange= {event => {handleChange(event)}} type={type} name={name} min={min} max={max} required={required} pattern={pattern}/>
                </div>
            )
            break;
        case "textarea":
            InputTag = (
                <div className="textarea">
                    <label>{getLabel()}</label>
                    <textarea onChange= {handleChange} name={name} value={value} required={required}></textarea>
                </div>
            )
            break;
        default:
            InputTag = (
                <div className={"input" + (half ? " input--half" : "")}>
                    <label>{getLabel()}</label>
                    <input onChange= {event => {handleChange(event)}} type={type} name={name} required={required} pattern={pattern}/>
                </div>
            )
    }

    return (
        <>
            { InputTag }
        </>
        
    )
}

InputField.propTypes = {
    name: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
    label: PropTypes.string.isRequired,
    required: PropTypes.bool,
    pattern: PropTypes.string,
    defaultValue: PropTypes.string,
    options: PropTypes.array,
    selectHint: PropTypes.string,
    onChange: PropTypes.func
}

export default InputField
