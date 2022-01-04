/*
*   External dependecies
*/
import React, { useEffect, useState } from 'react'
import { __ } from '@wordpress/i18n';
import qs from "qs";
import {ErrorBoundary} from 'react-error-boundary'
/*
*   Internal dependecies
*/
import UserRegistration from './Components/UserRegistration.jsx'
import TicketList from "./Components/TicketList.jsx"
import Payment from "./Components/Payment.jsx"
import ErrorFallback from './Components/Error.jsx';
import Gateway from './Components/Gateway.jsx';
import OfflinePayment from './Components/OfflinePayment.jsx';

let openBookingModal = () => {}

const Booking = () => {

    const [error, setError] = useState("");
    const [modalVisible, setModalVisible] = useState(false);
    const [message, setMessage] = useState("");
    const [originalDocumentTitle, setOriginalDocumentTitle] = useState("");
    const [loading, setLoading] = useState(false);
    const [wizzardStep, setWizzardStep] = useState(() => 0);
    const [ticketSelection, setTicketSelection] = useState(() => []);
    const [coupon, setCoupon] = useState(() => { return {}});
    const [gateway, setGateway] = useState(() => { return "offline"});
	const [bookingId, setBookingId] = useState(0);

    const [formData, setFormData] = useState({});

    const ticketPrice = (key) => {
      return eventData.tickets[key].price * ticketSelection.reduce((n, ticket) => {
          return n + (ticket.id == eventData.tickets[key].id);
      }, 0);
    }

    const changeCoupon = (coupon) => {
      setCoupon(coupon);
    }

    const currentGateway = () => {
      if(eventData.gateways == undefined) return null
      const index = eventData.gateways.findIndex((element) => { return element.id === gateway });
      return eventData.gateways[index]
    }

    const updateGateway = (gateway) => {
      setGateway(gateway)
    }

   const countTickets = (id) => {
    let count = ticketSelection.reduce((n, ticket) => {
      return n + (ticket.id == id);
    }, 0);
    return count;
   }

   openBookingModal = () => {
     setModalVisible(true);
   }
  
  const fullPrice = () => {
      let sum = 0;
      
      for(let ticket in eventData.tickets) {
          sum += ticketPrice(ticket)
      }

      if(!coupon.success) return sum;

      if(!coupon.percent) return sum - parseInt(coupon.discount)
      
      return sum - (sum / 100 * parseInt(coupon.discount))
  }

    const addTicket = (id) => {
      let ticket = {...eventData.tickets[id]}
      ticket.uid = Math.round(Math.random() * 1000)
      setTicketSelection(data => [...data, ticket])
    }

    const removeTicket = (uid) => {
      setTicketSelection(selection => selection.filter((ticket) => ticket.uid !== uid))
    }

    const removeTicketByType = (type) => {
      const indexToRemove = ticketSelection.findIndex((element) => { return element.id === parseInt(type) });
      setTicketSelection(selection => selection.filter((ticket, index) => index !== indexToRemove))
    }

    const updateTicket = (id, ticket) => {
      setTicketSelection(selection => [...selection.slice(0,id),ticket,...selection.slice(id+1)])
    }

    const updateForm = (field, value) => {
      setFormData({...formData, [field]: value})
  
    }

    const {booking_nonce, rest_url, booking_url, wp_debug} = window.bookingAppData
    const eventData = {
      event: window.bookingAppData.event,
      coupons: window.bookingAppData.coupons,
      fields: window.bookingAppData.fields,
      attendee_fields: window.bookingAppData.attendee_fields,
      tickets: window.bookingAppData.tickets,
      gateways: window.bookingAppData.gateways,
      strings: window.bookingAppData.strings
    }

    const openModal = () => {
      document.title = `${__('Registration', 'em-pro')} ${eventData.event.event_name}`;
      setModalVisible(true);
    }

    const closeModal = () => {
      document.title = originalDocumentTitle;
      setModalVisible(false);
    }
   
    useEffect(() => {
      setOriginalDocumentTitle(document.title)
      if(eventData.attendee_fields.length === 0) setWizzardStep(1)
      let tempForm = {}
      for(let field of eventData.fields) {
        tempForm[field.name] = field.value
      }
      tempForm["data_privacy_consent"] = ""
      setFormData(tempForm);
      for(let ticketKey in eventData.tickets) {
        for(let i = 0; i < eventData.tickets[ticketKey].min; i++) {
          let ticket = {...eventData.tickets[ticketKey]}
          ticket.uid = Math.floor(Math.random() * 1000)
          setTicketSelection([...ticketSelection, ticket])
        }
      }
          
      }, [])

      if(Object.keys(eventData).length == 0) return (<span className="button button--error button-pseudo">{__('Error: No connection to server.', 'em-pro')}</span>);

      const order = () => {

        setLoading(true)
        const request = formData
        request['_wpnonce'] = booking_nonce
        request['action'] = "booking_add"
        request['em_attendee_fields'] = {}
        request['em_tickets'] = {}
        eventData.tickets.map((ticket) => {
            request['em_attendee_fields'][ticket.id] = []
            request['em_tickets'][ticket.id] = {spaces: 0}
        })
        
        ticketSelection.map((ticket) => {
          request.em_attendee_fields[ticket.id].push(ticket.fields)
          request.em_tickets[ticket.id].spaces +=1
        })

        if(coupon.code != undefined) {
          request["coupon_code"] = coupon.code
        }

        request["gateway"] = gateway
        request['event_id'] = eventData.event.event_id

        const url = new URL(booking_url)
        url.search = qs.stringify(request)
        fetch(url).then((response) => response.json()).then((response) => {
			
			if(!response.result) {
				setLoading(false)
				setError(response.errors)
				return;
			}
			if(response.gateway === "mollie") {
				window.location.replace(response.mollie_url);
			}
			if(response.gateway === "offline") {
				setBookingId(response.booking_id)
			}
		  	setMessage(response.message);
			setWizzardStep(3)
			setLoading(false)
			return;
	
        })

        // Show warning 
      }

      const cleanUp = () => {
        closeModal()
        if(eventData.attendee_fields.length === 0) setWizzardStep(1)
        let tempForm = {}
        for(let field of eventData.fields) {
          tempForm[field.name] = field.value
        }
        tempForm["data_privacy_consent"] = ""
        setFormData(tempForm);
        for(let ticketKey in eventData.tickets) {
          for(let i = 0; i < eventData.tickets[ticketKey].min; i++) {
            let ticket = {...eventData.tickets[ticketKey]}
            ticket.uid = Math.floor(Math.random() * 1000)
            setTicketSelection([...ticketSelection, ticket])
          }
        }
      }

      const pageTitles = [
        __("Choose your Tickets first", "em-pro"),
        __("Please give us some more data", "em-pro"),
        __("How do you want to pay?", "em-pro"),
        __("Thank you for your order!", "em-pro"),
      ]

    
      
      return (
        <div>
          <ErrorBoundary FallbackComponent={ErrorFallback}>
          <button className="button button--primary" onClick={() => {openModal()}}>{ eventData?.strings?.modal_button }</button>
          
          <div className={`modal modal--fullscreen ${wizzardStep == 3 ? " modal--success" : ""} ${modalVisible ? "modal--open" : ""}`}>
            { loading && <div className="modal__overlay">
              <aside>
                <div className="spinning-loader"></div>
                <h4>{eventData?.strings.loading}</h4>
                <h5>{eventData?.strings.dont_close}</h5>
              </aside>
            </div> }
            <div className="modal__dialog">
              <div className="modal__header">
                  <div className="modal__title"><h2>{pageTitles[wizzardStep]}</h2></div>
                  <button className="modal__close" onClick={() => {closeModal()}}></button>
              </div>
              
                <div className="modal__content">
                  <div className="wizzard">
                <div className={`wizzard__step ${wizzardStep == 0 ? " wizzard__step--active" : ""} ${wizzardStep == 1 ? " wizzard__step--prev" : ""}`}>
                  <div className="container">
                    <div className="section">
                    <TicketList 
                      eventData={eventData}
                      coupon={coupon}
                      ticketSelection={ticketSelection}
                      addTicket={addTicket}
                      updateTicket={updateTicket}
                      removeTicket={removeTicket}
                      ticketPrice={ticketPrice}
                      fullPrice={fullPrice}
                      countTickets={countTickets}
                    />
                    </div>
                    </div>
                    
                  </div>

                  <div className={`wizzard__step ${wizzardStep == 1 ? " wizzard__step--active" : ""} ${wizzardStep == 2 && fullPrice() != 0 ? " wizzard__step--prev" : ""} ${wizzardStep == 0 ? " wizzard__step--next" : ""}`}>
                  <div className="container">
                        <UserRegistration 
                          eventData={eventData}
                          coupon={coupon}
                          addTicket={addTicket}
                          removeTicketByType={removeTicketByType}
                          tickets={eventData.tickets}
                          ticketPrice={ticketPrice}
                          error={error}
                          fullPrice={fullPrice}
                          countTickets={countTickets}
                          ticketSelection={ticketSelection}
                          fields={eventData.fields}
                          formData={formData}
                          updateForm={updateForm}
                        />
                  </div>
                    
                  </div>
            
                  <div className={`wizzard__step ${wizzardStep == 2 ? " wizzard__step--active" : ""} ${wizzardStep == 3 ? "" : ""} ${wizzardStep == 1 ? " wizzard__step--next" : ""}`}>
                  <div className="container">
                  <Payment 
                      eventData={eventData}
                      coupon={coupon}
                      changeCoupon={changeCoupon}
                      formData={formData}
                      error={error}
                      currentGatewayId={gateway}
                      updateGateway={updateGateway}
                      updateForm={updateForm}
                      ticketPrice={ticketPrice}
                      fullPrice={fullPrice}
                      rest_url={rest_url}
                    />
                        
                  </div>
                    
                  </div>
      
                  <div className={`wizzard__step ${wizzardStep == 3 ? " wizzard__step--active" : ""} ${wizzardStep == 2 ? " " : ""}` }>
                  <div className="container">
                        {message}
						{ fullPrice() !== 0 &&
							<OfflinePayment 
								currentGateway={currentGateway()}
								bookingId={bookingId}
								eventData={eventData}
						  	/>
						}
                        
                  </div>
                    
                  </div>
                </div>
                </div>
              <div className="modal__footer">
                <div className="section">
                  <div className="container button-group button-group--right">
                    { wizzardStep > (eventData?.attendee_fields?.length == 0 ? 1 : 0) && wizzardStep < 3 && <button className="button button--secondary" onClick={() => {setWizzardStep(wizzardStep-1)}}>{__('Back', 'em-pro')}</button> }
                    { wizzardStep < (fullPrice() == 0 ? 1 : 2) && <button className="button button--primary" onClick={() => {setWizzardStep(wizzardStep+1)}} >{__('Next', 'em-pro')}</button> }
                    { wizzardStep == (fullPrice() == 0 ? 1 : 2) && <button className="button button--primary" onClick={() => {order()}}>{eventData.strings.book_now}</button> }
                    { wizzardStep == 3 && <button className="button button--success" onClick={() => {cleanUp()}}>{__("Close", "em-pro")}</button> }
                  </div>
                </div>
              </div>
            </div>
          </div> 
          </ErrorBoundary>
        </div>
    )
}

export { openBookingModal }
export default Booking;