var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;
var takeSuggestAmount = 10;
var takeRequestAmount = 10;
var takeConnectionAmount = 10;
/**
  * Fetch all Suggestions for user.
*/
function getSuggestions(mode) {
  $("#suggestion_table").hide();
  $("#connections_in_common_skeleton").show();
  $("#connection_table").hide();
  $("#connection_skeleton").hide();
  $("#request_table").hide();
  $("#load_more_btn_request").hide();
  var url = "/suggestion/users";
  $.ajax
    ({
        type: "GET",
        url: url,
        success: function(data)
        {
          $("#get_suggestions_btn").html('Suggestions ('+data.totalUsers+')');
          $("#get_sent_requests_btn").html('Sent Requests ('+data.totalRequest+')');
          $("#get_received_requests_btn").html('Received Requests ('+data.totalReceive+')');
          $("#get_connections_btn").html('Connections ('+data.totalConnected+')');
          $("#suggestion_table").show();
          $("#connections_in_common_skeleton").hide();
          $("#load_more_btn").show();
          var html = '';
          for (var i = data.users.length - 1; i >= 0; i--) {
            html +='<div class="d-flex justify-content-between">';
              html += "<table class='ms-1'>";
                html +=`<td class="align-middle">${data.users[i].name}</td>`;
                html +='<td class="align-middle"> - </td>';
                html +=`<td class="align-middle">${data.users[i].email}</td>`
                html +='<td class="align-middle">'
              html +="</table>"
              html +="<div>"
                html +=`<button id="create_request_btn_${data.users[i].id}" user-id="${data.user_id}" suggestion-id="${data.users[i].id}" class="btn btn-primary me-1">Connect</button>`
              html +="</div>";
              html +="</div><br>";
          }
          $("#suggestion_table").html(html);
          for (var i = data.users.length - 1; i >= 0; i--) {
            const btns = document.querySelector('#create_request_btn_'+data.users[i].id);
            const user_id = $('#create_request_btn_'+data.users[i].id).attr('user-id');
            const suggestion_id = $('#create_request_btn_'+data.users[i].id).attr('suggestion-id');
            btns.addEventListener('click', function (e) {
              sendRequest(user_id,suggestion_id);
            });
          }
        }
    });
}

/**
  * Fetch all Suggestions for user for show more.
*/
function getMoreSuggestions() {
  $("#suggestion_table").hide();
  $("#connections_in_common_skeleton").show();
  takeSuggestAmount+=10;
  var url = "/suggestion/users/more/"+takeSuggestAmount;
  $.ajax
    ({
      type: "GET",
      url: url,
      success: function(data)
      {
          $("#suggestion_table").show();
          $("#connections_in_common_skeleton").hide();
          $("#load_more_btn").show();
          if (takeSuggestAmount>data.totalUsers) {
            $("#load_more_btn").hide();
          }
          var html = '';
          if (data.users.length>0) {
            for (var i = data.users.length - 1; i >= 0; i--) {
              html +='<div class="d-flex justify-content-between">';
                html += "<table class='ms-1'>";
                  html +=`<td class="align-middle">${data.users[i].name}</td>`;
                  html +='<td class="align-middle"> - </td>';
                  html +=`<td class="align-middle">${data.users[i].email}</td>`
                  html +='<td class="align-middle">'
                html +="</table>"
                html +="<div>"
                  html +=`<button id="create_request_btn_${data.users[i].id}" user-id="${data.user_id}" suggestion-id="${data.users[i].id}" class="btn btn-primary me-1">Connect</button>`
                html +="</div>";
                html +="</div><br>";
            }
            $("#suggestion_table").html(html);
            for (var i = data.users.length - 1; i >= 0; i--) {
              const btns = document.querySelector('#create_request_btn_'+data.users[i].id);
              const user_id = $('#create_request_btn_'+data.users[i].id).attr('user-id');
              const suggestion_id = $('#create_request_btn_'+data.users[i].id).attr('suggestion-id');
              btns.addEventListener('click', function (e) {
                sendRequest(user_id,suggestion_id);
              });
            }
          }else{
            $("#load_more_btn").hide();
          }
      }
    });
}

/**
  * Fetch all Requests for user.
*/
function getRequests(mode){
  $("#suggestion_table").hide();
  $("#load_more_btn").hide();
  $("#connection_table").hide();
  $("#connection_skeleton").hide();
  $("#request_skeleton").show();
  $("#request_table").hide();
  var url = "/request/users";
  $.ajax
    ({
        type: "GET",
        url: url,
        success: function(data)
        {
          $("#get_sent_requests_btn").html('Sent Requests ('+data.totalUsers+')');
          $("#request_table").show();
          $("#request_skeleton").hide();
          if (data.totalRequest>10) {
            $("#load_more_btn_request").show();
          }
          var html = '';
          for (var i = data.users.length - 1; i >= 0; i--) {
            html += `<div class="d-flex justify-content-between">
              <table class="ms-1">
                <td class="align-middle">${data.users[i].receivers.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${data.users[i].receivers.email}</td>
                <td class="align-middle">
              </table>
              <div>
                  <button id="cancel_request_btn_${data.users[i].id}" user-id="${data.user_id}" request-id="${data.users[i].receiver_user_id}" class="btn btn-danger me-1"
                    onclick="">Withdraw Request</button>
              </div>
            </div><br>`;
          }
          $("#request_table").html(html);
            for (var i = data.users.length - 1; i >= 0; i--) {
              //Binding click event on dynmaic created withdraw request button
              const btns = document.querySelector('#cancel_request_btn_'+data.users[i].id);
              const cancel_user_id = $('#cancel_request_btn_'+data.user_id
                ).attr('user-id');
              const cancel_request_id = $('#cancel_request_btn_'+data.users[i].id
                ).attr('request-id');
              btns.addEventListener('click', function (e) {
                deleteRequest(cancel_user_id,cancel_request_id);
              });
            }
        }
    });
}

/**
  * Fetch all Requests for user for show more.
*/
function getMoreRequests()
{
  $("#suggestion_table").hide();
  $("#load_more_btn").hide();
  $("#connection_table").hide();
  $("#connection_skeleton").hide();
  $("#request_skeleton").show();
  takeRequestAmount+=10;
  var url = "/request/users/more/"+takeRequestAmount;
  $.ajax
    ({
        type: "GET",
        url: url,
        success: function(data)
        {
          $("#get_sent_requests_btn").html('Sent Requests ('+data.totalUsers+')');
          $("#request_table").show();
          $("#request_skeleton").hide();
          if (takeRequestAmount>data.totalUsers) {
            $("#load_more_btn_request").show();
          }
          var html = '';
          if (data.users.length>0) {
            for (var i = data.users.length - 1; i >= 0; i--) {
              html += `<div class="d-flex justify-content-between">
                <table class="ms-1">
                  <td class="align-middle">${data.users[i].receivers.name}</td>
                  <td class="align-middle"> - </td>
                  <td class="align-middle">${data.users[i].receivers.email}</td>
                  <td class="align-middle">
                </table>
                <div>
                    <button id="cancel_request_btn_${data.users[i].id}" user-id="${data.user_id}" request-id="${data.users[i].receiver_user_id}" class="btn btn-danger me-1"
                      onclick="">Withdraw Request</button>
                </div>
              </div><br>`;
            }
            $("#request_table").html(html);
              for (var i = data.users.length - 1; i >= 0; i--) {
                //Binding click event on dynmaic created withdraw request button
                const btns = document.querySelector('#cancel_request_btn_'+data.users[i].id);
                const cancel_user_id = $('#cancel_request_btn_'+data.user_id
                  ).attr('user-id');
                const cancel_request_id = $('#cancel_request_btn_'+data.users[i].id
                  ).attr('request-id');
                btns.addEventListener('click', function (e) {
                  deleteRequest(cancel_user_id,cancel_request_id);
                });
              }
          }else{
            $("#load_more_btn_request").hide();
          }
        }
    });
}

/**
  * Fetch all Received Requests for user
*/
function getReceiveRequests(mode){
  $("#suggestion_table").hide();
  $("#load_more_btn").hide();
  $("#connection_table").hide();
  $("#connection_skeleton").hide();
  $("#request_skeleton").show();
  $("#request_table").hide();
  var url = "/request/receive/users";
  $.ajax
    ({
        type: "GET",
        url: url,
        success: function(data)
        {
          $("#get_received_requests_btn").html('Received Requests ('+data.totalUsers+')');
          $("#request_table").show();
          $("#request_skeleton").hide();
          if (data.totalRequest>10) {
            $("#load_more_btn_request").show();
          }
          var html = '';
          for (var i = data.users.length - 1; i >= 0; i--) {
            html += `<div class="d-flex justify-content-between">
              <table class="ms-1">
                <td class="align-middle">${data.users[i].senders.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${data.users[i].senders.email}</td>
                <td class="align-middle">
              </table>
              <div>
                  <button id="accept_request_btn_${data.users[i].id}" user-id="${data.user_id}" request-id="${data.users[i].sender_user_id}" class="btn btn-success me-1"
                    onclick="">Accept</button>
              </div>
            </div><br>`;
          }
          $("#request_table").html(html);
            for (var i = data.users.length - 1; i >= 0; i--) {
              //Binding click event on dynmaic created accept request button
              const btns = document.querySelector('#accept_request_btn_'+data.users[i].id);
              const accept_user_id = $('#accept_request_btn_'+data.user_id
                ).attr('user-id');
              const accept_request_id = $('#accept_request_btn_'+data.users[i].id
                ).attr('request-id');
              btns.addEventListener('click', function (e) {
                acceptRequest(accept_user_id,accept_request_id);
              });
            }
        }
    });
}

/**
  * Fetch all connections for user
*/
function getConnections() {
  $("#suggestion_table").hide();
  $("#request_table").hide();
  $("#load_more_btn").hide();
  $("#load_more_btn_request").hide();
  $("#connection_skeleton").show();
  var url = "/connection/users";
  $.ajax
    ({
        type: "GET",
        url: url,
        success: function(data)
        {
          $("#get_connections_btn").html('Connections ('+data.totalUsers+')');
          $("#connection_table").show();
          $("#connection_skeleton").hide();
          if (data.totalUsers>10) {
            $("#load_more_btn_connection").show();
          }
          var html = '';
          for (var i = data.users.length - 1; i >= 0; i--) {
            html += `<div class="d-flex justify-content-between">
                      <table class="ms-1">
                        <td class="align-middle">${data.users[i].users.name}</td>
                        <td class="align-middle"> - </td>
                        <td class="align-middle">${data.users[i].users.email}</td>
                        <td class="align-middle">
                      </table>
                      <div>
                        <button style="width: 220px" id="get_connections_in_common_${data.users[i].users.id}" class="btn btn-primary" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapse_${data.users[i].id}" aria-expanded="false" aria-controls="collapseExample">
                          Connections in common 
                        </button>
                        <button id="remove_connection_btn_${data.users[i].users.id}" connected-id="${data.users[i].users.id}" class="btn btn-danger me-1">Remove Connection</button>
                      </div>
                    </div><br>
                    <div class="collapse" id="collapse_${data.users[i].id}">
                      <div id="content_${data.users[i].id}" class="p-2">
                        
                      </div>
                      <div id="connections_in_common_skeletons_${data.users[i].users.id}">
                        
                      </div>
                    </div>`;
          }
          $("#connection_table").html(html);
            for (var i = data.users.length - 1; i >= 0; i--) {
              //Binding click event on dynmaic created remove connection button
              const btns = document.querySelector('#get_connections_in_common_'+data.users[i].users.id);
              const connected_user_id = data.users[i].users.id;
              const user_id = data.users[i].id;
              btns.addEventListener('click', function (e) {
                getConnectionsInCommon(connected_user_id,user_id);
              });
            }
            for (var i = data.users.length - 1; i >= 0; i--) {
              //Binding click event on dynmaic created remove connection button
              const btns = document.querySelector('#remove_connection_btn_'+data.users[i].users.id);
              const connected_user_id = data.users[i].users.id;
              btns.addEventListener('click', function (e) {
                removeConnection(connected_user_id);
              });
            }
        }
    });
}

/**
  * Fetch all connections for user on show more
*/
function getMoreConnections() {
  $("#suggestion_table").hide();
  $("#request_table").hide();
  $("#load_more_btn").hide();
  $("#load_more_btn_request").hide();
  $("#connection_skeleton").show();
  takeConnectionAmount+=10;
  var url = "/connection/users/more/"+takeConnectionAmount;
  $.ajax
    ({
        type: "GET",
        url: url,
        success: function(data)
        {
          $("#get_connections_btn").html('Connections ('+data.totalUsers+')');
          $("#connection_table").show();
          $("#connection_skeleton").hide();
          if (takeConnectionAmount>data.totalUsers) {
            $("#load_more_btn_connection").hide();
          }
          var html = '';
          for (var i = data.users.length - 1; i >= 0; i--) {
            html += `<div class="d-flex justify-content-between">
                      <table class="ms-1">
                        <td class="align-middle">${data.users[i].users.name}</td>
                        <td class="align-middle"> - </td>
                        <td class="align-middle">${data.users[i].users.email}</td>
                        <td class="align-middle">
                      </table>
                      <div>
                        <button style="width: 220px" id="get_connections_in_common_${data.users[i].users.id}" class="btn btn-primary" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapse_${data.users[i].id}" aria-expanded="false" aria-controls="collapseExample">
                          Connections in common 
                        </button>
                        <button id="remove_connection_btn_${data.users[i].users.id}" connected-id="${data.users[i].users.id}" class="btn btn-danger me-1">Remove Connection</button>
                      </div>
                    </div><br>
                    <div class="collapse" id="collapse_${data.users[i].id}">
                      <div id="content_${data.users[i].id}" class="p-2">
                        
                      </div>
                      <div id="connections_in_common_skeletons_${data.users[i].users.id}">
                        
                      </div>
                    </div>`;
          }
          $("#connection_table").html(html);
            for (var i = data.users.length - 1; i >= 0; i--) {
              //Binding click event on dynmaic created remove connection button
              const btns = document.querySelector('#get_connections_in_common_'+data.users[i].users.id);
              const connected_user_id = data.users[i].users.id;
              const user_id = data.users[i].id;
              btns.addEventListener('click', function (e) {
                getConnectionsInCommon(connected_user_id,user_id);
              });
            }
            for (var i = data.users.length - 1; i >= 0; i--) {
              //Binding click event on dynmaic created remove connection button
              const btns = document.querySelector('#remove_connection_btn_'+data.users[i].users.id);
              const connected_user_id = data.users[i].users.id;
              btns.addEventListener('click', function (e) {
                removeConnection(connected_user_id);
              });
            }
        }
    });
}

/**
  * Fetch all connections for user in common
*/
function getConnectionsInCommon(connectionId,user_id) {
  var url = "/connection/inCommon";
  $.ajax({
    url: url,
    type: "GET",
    data: { 
      connection_id: connectionId,
    },
    success: function(data) {
      var html = '';
      if (data.users.length>0) {
        for (var i = data.users.length - 1; i >= 0; i--) {
         html +=`<div class="p-2 shadow rounded mt-2  text-white bg-dark">Name - ${data.users[i].users.name}</div>`
        }
        $(`#content_${user_id}`).html(html);
      }else{
        $(`#get_connections_in_common_${user_id}`).prop('disabled', true);
      } 
    },
    error: function(error) {
      alert('something went wrong');
    }
  });
}


function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

/**
  * Send request to user
*/
function sendRequest(userId, suggestionId) {
  var url = "/suggestion/sendRequest";
  $.ajax({
    url: url,
    type: "GET",
    data: { 
      user_id: userId, 
      suggestion_id: suggestionId,
    },
    success: function(response) {
      // console.log('kjadf',response);
      getSuggestions();
    },
    error: function(error) {
      // console.log('kjadf',error);
    }
  });
}

/**
  * delete request for user
*/
function deleteRequest(userId, requestId) {
  var url = "/request/deleteRequest";
  $.ajax({
    url: url,
    type: "GET",
    data: { 
      user_id: userId, 
      request_id: requestId,
    },
    success: function(response) {
      getRequests();
    },
    error: function(error) {

    }
  });
}

/**
  * Accept request for user
*/
function acceptRequest(userId, requestId) {
  var url = "/request/acceptRequest";
  $.ajax({
    url: url,
    type: "GET",
    data: { 
      user_id: userId, 
      request_id: requestId,
    },
    success: function(response) {
      getReceiveRequests();
    },
    error: function(error) {
      alert('something went wrong');
    }
  });
}

/**
  * Remove connection for user
*/
function removeConnection(connectionId) {
  var url = "/connection/removeConnection";
  $.ajax({
    url: url,
    type: "GET",
    data: {  
      connection_id: connectionId,
    },
    success: function(response) {
      getConnections();
    },
    error: function(error) {
      alert('something went wrong');
    }
  });
}

$(function () {
  //getSuggestions();
});