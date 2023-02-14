import {useEffect, useState} from "react";

import axiosClient from "../axios-client.js";
function Availability() {
    const [appointments, setAppointments] = useState([]);
    useEffect(() => {
        axiosClient.get('/appointments')
            .then(response => {
                setAppointments(response.data.data);
                console.log(response.data.data);


            })
            .catch(error => {
                console.error(error);
            });
    }, []);

    const appointmentDates =  appointments.map(appointment => new Date(appointment.date));
    console.log(appointmentDates);
    return (<div>
        <h1>Availability</h1>

    </div>)
}
export default Availability
