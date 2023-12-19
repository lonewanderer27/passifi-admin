import "bootstrap/dist/css/bootstrap.min.css";
import React, {useEffect, useState} from "react";
import {QrReader} from "react-qr-reader";
import axios from "axios";
import {Col, Container, Row, Tab, Tabs} from "react-bootstrap";
import Collapse from 'react-bootstrap/Collapse';

const EVENT = "event";
const GUEST = "guest";
const GUEST_DENIED = "guest_denied";
const LOADING = "loading";

export default function AdminScan({event, attendeesApiRoute}) {
    const [user, setUser] = useState(null); // [id, name, email, email_verified_at, created_at, updated_at]
    const [attendee, setAttendee] = useState(null); // [user_id, event_id, approved, pending]
    const [activeTab, setActiveTab] = useState(EVENT); // [event, guest, guest_denied
    const [qrData, setQrData] = useState(null);
    const [res, setRes] = useState(null);

    useEffect(() => {
        // check if data is not null
        if (qrData) {
            // log the raw data
            console.log(qrData);

            try {
                // convert JSON into Javascript Object
                const rawJson = JSON.parse(qrData);

                // verify if the ff exists:
                // id
                // user_id
                // event_id
                // pending
                // approved

                // if one of these properties do not exist, show an error
                // if all of these properties exist, then we can proceed to the next step
                if (
                    !rawJson.hasOwnProperty("user_id") &&
                    !rawJson.hasOwnProperty("event_id")
                ) {
                    console.error("Invalid QR Code");
                    alert("Invalid QR Code");
                    return;
                }

                // freeze the video
                document.getElementById("preview").pause();

                // set the tab to loading
                setActiveTab(LOADING)

                // add a delay
                setTimeout(() => {

                    // make a post request to the server
                    axios.post(attendeesApiRoute, {
                        user_id: rawJson.user_id,
                        event_id: rawJson.event_id,
                    }).then((res) => {
                        console.log("axios.post then")

                        // log the response
                        console.log(res.data);

                        // set the attendee
                        setAttendee(res.data.attendee);

                        // set the user
                        setUser(res.data.user);

                        // set the active tab
                        setActiveTab(GUEST)

                        // set the response
                        setRes(res.data);
                    })
                        .catch((err) => {
                            console.log("axios.post catch")
                            // log the response
                            console.error(err.response.data);

                            // set the user
                            setUser(err.response.data.user);

                            // set the active tab
                            setActiveTab(GUEST_DENIED)

                            // set the response
                            setRes(err.response.data);
                        }).finally(() => {
                        console.log("axios.post finally")

                        reset()
                    })

                }, 3000)


            } catch (e) {
                console.error(e);
                alert("Invalid QR Code");
            }
        }
    }, [qrData])

    function reset() {
        setTimeout(() => {
            // unfreeze the video
            document.getElementById("preview").play();

            // set the active tab
            setActiveTab(EVENT)

            // set the qr data to null
            setQrData(null);

            // set the user to null
            setUser(null);

            // set the attendee to null
            setAttendee(null);

            // set the response to null
            setRes(null)
        }, 8000);
    }

    return (
        <>
            <nav className="navbar bg-body-tertiary">
                <div className="container-fluid">
                    <a className="navbar-brand" href="/">
                        <img src="/images/passifi-logo.png" alt="Logo" height="50"
                             className="d-inline-block align-text-top"/>
                    </a>
                </div>
            </nav>
            <div style={{height: "80vh"}}
                 className="container d-flex flex-column justify-content-center align-content-center w-75">
                <div className="card text-center">
                    <div className="card-body h-50">
                        <Container>
                            <Row>
                                <Col>
                                    <Tabs transition={Collapse} className="d-flex align-items-center"
                                          activeKey={activeTab}>
                                        <Tab eventKey={EVENT} bsPrefix={"my-5 tab"}>
                                            <div className={'container h-100'}>
                                                <div className="row h-100 d-flex align-items-center">
                                                    <div className="col d-flex justify-content-center px-2  py-2">
                                                        <img src={event.avatar} alt="QR Code" height="200"/>
                                                    </div>
                                                    <div className="col px-5">
                                                        <div className="event-details">
                                                            <h2 className="mb-3">{event.title}</h2>

                                                            <div className="event-info">
                                                                <div
                                                                    className={"d-flex flex-col align-content-center justify-content-center"}>
                                                                    <i className="fs-2 me-2 ri-calendar-2-fill stat--icon"></i>
                                                                    <p className={"mt-2"}>{new Date(event.date).toDateString()}</p>
                                                                </div>
                                                                <div
                                                                    className={"d-flex flex-col align-content-center justify-content-center"}>
                                                                    <i className="fs-4 me-2 ri-time-fill stat--icon"></i>
                                                                    <p className={"mt-2"}>{event.time}</p>
                                                                </div>
                                                                <div
                                                                    className={"d-flex flex-col align-content-center justify-content-center"}>
                                                                    <i className="fs-4 me-2 ri-map-pin-2-fill map--pin"></i>
                                                                    <p className={"mt-2"}>{event.location}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </Tab>
                                        <Tab eventKey={GUEST} bsPrefix={"my-5 tab"}>
                                            {activeTab === GUEST && (
                                                <div className="container h-100">
                                                    <div className="row h-100 d-flex align-items-center">
                                                        {/* Display user information and allowed message */}
                                                        {user && (
                                                            <div className="col px-5">
                                                                <h2 className="mb-3">{user.name}</h2>
                                                                <p>User ID: {user.id}</p>
                                                                <p>Email: {user.email}</p>
                                                                <p>Status: Allowed to enter</p>
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>)}
                                        </Tab>
                                        <Tab eventKey={GUEST_DENIED} bsPrefix={"my-5 tab"}>
                                            {activeTab === GUEST_DENIED && (
                                                <div className="container h-100">
                                                    <div className="row h-100 d-flex align-items-center">
                                                        {/* Display user information and denied message */}
                                                        {user && (
                                                            <div className="col px-5">
                                                                <h2 className="mb-3">{user.name}</h2>
                                                                <p>User ID: {user.id}</p>
                                                                <p>Email: {user.email}</p>
                                                                <p>Status: {res.message}</p>
                                                            </div>
                                                        )}
                                                        {res.error === "Validation failed" && (
                                                            <div className="col px-5 py-5">
                                                                <p className={"fs-5 fw-bold"}>Error: {Object.values(
                                                                    res.errors
                                                                ).flat().join("\n").includes("unique") ? "You've already scanned!" : Object.values(
                                                                    res.errors
                                                                ).flat().join("\n").includes("unique")}</p>
                                                            </div>
                                                        )}
                                                        {res.error !== "Validation failed" && (
                                                            <div className="col px-5 py-5">
                                                                <p className={"fs-5 fw-bold"}>Unknown Error. Please have a look in the console to fix it!</p>
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                            )}
                                        </Tab>
                                        <Tab eventKey={LOADING} bsPrefix={"my-5 tab"}>
                                            <div className="container h-100">
                                                <div
                                                    className="row h-100 d-flex align-items-center flex-col justify-content-center py-4">
                                                    <div className="spinner-border"
                                                         style={{width: "10rem", height: "10rem"}} role="status">
                                                        <span className="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </Tab>
                                    </Tabs>
                                </Col>
                                <Col xs={4}>
                                    <QrReader
                                        videoId={"preview"}
                                        onResult={(result) => {
                                            if (!!result) {
                                                setQrData(result?.text);
                                            }
                                        }}
                                        style={{width: "100%", height: "500px"}}
                                        constraints={{facingMode: "user"}}
                                    />
                                </Col>
                            </Row>
                        </Container>
                    </div>
                </div>
            </div>
        </>
    );
}
