<?php

namespace WebSockets\Status; // Namespaces of current service

/**
 * Response error numbers by Windows Platform
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/Websocket/src/Websocket/Status/WinSocketErrors.php
 */

class WinSocketErrors {

    /**
     * get($e) return message by code
     * @param int $e error code
     * @access static
     * @return string
     */
    public static function get($e)
    {
	if(array_key_exists($e, self::$message))  return self::$message[$e];
	else return self::$message[0];
    }
    
    /**
     * $message
     * @var array 
     */
    public static $message = [
	'0' => "Unknown error",
	'6' => "Specified event object handle is invalid.\nAn application attempts to use an event object, but the specified handle is not valid. Note that this error is returned by the operating system, so the error number may change in future releases of Windows.",
	'8' => "Insufficient memory available.\nAn application used a Windows Sockets function that directly maps to a Windows function. The Windows function is indicating a lack of required memory resources. Note that this error is returned by the operating system, so the error number may change in future releases of Windows.",
	'87' => "One or more parameters are invalid.\nAn application used a Windows Sockets function which directly maps to a Windows function. The Windows function is indicating a problem with one or more parameters. Note that this error is returned by the operating system, so the error number may change in future releases of Windows.",
	'995' => "Overlapped operation aborted.\nAn overlapped operation was canceled due to the closure of the socket, or the execution of the SIO_FLUSH command in WSAIoctl. Note that this error is returned by the operating system, so the error number may change in future releases of Windows.",
	'996' => "Overlapped I/O event object not in signaled state.\nThe application has tried to determine the status of an overlapped operation which is not yet completed. Applications that use WSAGetOverlappedResult (with the fWait flag set to FALSE) in a polling mode to determine when an overlapped operation has completed, get this error code until the operation is complete. Note that this error is returned by the operating system, so the error number may change in future releases of Windows.",
	'997' => "Overlapped operations will complete later.\nThe application has initiated an overlapped operation that cannot be completed immediately. A completion indication will be given later when the operation has been completed. Note that this error is returned by the operating system, so the error number may change in future releases of Windows.",
	'10004' => "Interrupted function call.\nA blocking operation was interrupted by a call to WSACancelBlockingCall.",
	'10009' => "File handle is not valid.\nThe file handle supplied is not valid.",
	'10013' => "Permission denied.\n
An attempt was made to access a socket in a way forbidden by its access permissions. An example is using a broadcast address for sendto without broadcast permission being set using setsockopt(SO_BROADCAST).\nAnother possible reason for the WSAEACCES error is that when the bind function is called (on Windows NT 4.0 with SP4 and later), another application, service, or kernel mode driver is bound to the same address with exclusive access. Such exclusive access is a new feature of Windows NT 4.0 with SP4 and later, and is implemented by using the SO_EXCLUSIVEADDRUSE option.",
	'10014' => "Bad address.\nThe system detected an invalid pointer address in attempting to use a pointer argument of a call. This error occurs if an application passes an invalid pointer value, or if the length of the buffer is too small. For instance, if the length of an argument, which is a sockaddr structure, is smaller than the sizeof(sockaddr).",
	'10022' => "Invalid argument.\nSome invalid argument was supplied (for example, specifying an invalid level to the setsockopt function). In some instances, it also refers to the current state of the socket—for instance, calling accept on a socket that is not listening.",
	'10024' => "Too many open files.\nToo many open sockets. Each implementation may have a maximum number of socket handles available, either globally, per process, or per thread.",
	'10035' => "Resource temporarily unavailable.\nThis error is returned from operations on nonblocking sockets that cannot be completed immediately, for example recv when no data is queued to be read from the socket. It is a nonfatal error, and the operation should be retried later. It is normal for WSAEWOULDBLOCK to be reported as the result from calling connect on a nonblocking SOCK_STREAM socket, since some time must elapse for the connection to be established.",
	'10036' => "Operation now in progress.\nA blocking operation is currently executing. Windows Sockets only allows a single blocking operation—per- task or thread—to be outstanding, and if any other function call is made (whether or not it references that or any other socket) the function fails with the WSAEINPROGRESS error.",
	'10037' => "Operation already in progress.\nAn operation was attempted on a nonblocking socket with an operation already in progress—that is, calling connect a second time on a nonblocking socket that is already connecting, or canceling an asynchronous request (WSAAsyncGetXbyY) that has already been canceled or completed.",
	'10038' => "Socket operation on nonsocket.\nAn operation was attempted on something that is not a socket. Either the socket handle parameter did not reference a valid socket, or for select, a member of an fd_set was not valid.",
	'10039' => "Destination address required.\nA required address was omitted from an operation on a socket. For example, this error is returned if sendto is called with the remote address of ADDR_ANY.",
	'10040' => "Message too long.\nA message sent on a datagram socket was larger than the internal message buffer or some other network limit, or the buffer used to receive a datagram was smaller than the datagram itself.",
	'10041' => "Protocol wrong type for socket.\nA protocol was specified in the socket function call that does not support the semantics of the socket type requested. For example, the ARPA Internet UDP protocol cannot be specified with a socket type of SOCK_STREAM.",
	'10042' => "Bad protocol option.\nAn unknown, invalid or unsupported option or level was specified in a getsockopt or setsockopt call.",
	'10043' => "Protocol not supported.\nThe requested protocol has not been configured into the system, or no implementation for it exists. For example, a socket call requests a SOCK_DGRAM socket, but specifies a stream protocol.",
	'10044' => "Socket type not supported.\nThe support for the specified socket type does not exist in this address family. For example, the optional type SOCK_RAW might be selected in a socket call, and the implementation does not support SOCK_RAW sockets at all.",
	'10045' => "Operation not supported.\nThe attempted operation is not supported for the type of object referenced. Usually this occurs when a socket descriptor to a socket that cannot support this operation is trying to accept a connection on a datagram socket.",
	'10046' => "Protocol family not supported.\nThe protocol family has not been configured into the system or no implementation for it exists. This message has a slightly different meaning from WSAEAFNOSUPPORT. However, it is interchangeable in most cases, and all Windows Sockets functions that return one of these messages also specify WSAEAFNOSUPPORT.",
	'10047' => "Address family not supported by protocol family.\nAn address incompatible with the requested protocol was used. All sockets are created with an associated address family (that is, AF_INET for Internet Protocols) and a generic protocol type (that is, SOCK_STREAM). This error is returned if an incorrect protocol is explicitly requested in the socket call, or if an address of the wrong family is used for a socket, for example, in sendto.",
	'10048' => "Address already in use.\nTypically, only one usage of each socket address (protocol/IP address/port) is permitted. This error occurs if an application attempts to bind a socket to an IP address/port that has already been used for an existing socket, or a socket that was not closed properly, or one that is still in the process of closing. For server applications that need to bind multiple sockets to the same port number, consider using setsockopt (SO_REUSEADDR). Client applications usually need not call bind at all—connect chooses an unused port automatically. When bind is called with a wildcard address (involving ADDR_ANY), a WSAEADDRINUSE error could be delayed until the specific address is committed. This could happen with a call to another function later, including connect, listen, WSAConnect, or WSAJoinLeaf.",
	'10049' => "Cannot assign requested address.\nThe requested address is not valid in its context. This normally results from an attempt to bind to an address that is not valid for the local computer. This can also result from connect, sendto, WSAConnect, WSAJoinLeaf, or WSASendTo when the remote address or port is not valid for a remote computer (for example, address or port 0).",
	'10050' => "Network is down.\nA socket operation encountered a dead network. This could indicate a serious failure of the network system (that is, the protocol stack that the Windows Sockets DLL runs over), the network interface, or the local network itself.",
	'10051' => "Network is unreachable.\nA socket operation was attempted to an unreachable network. This usually means the local software knows no route to reach the remote host.",
	'10052' => "Network dropped connection on reset.\nThe connection has been broken due to keep-alive activity detecting a failure while the operation was in progress. It can also be returned by setsockopt if an attempt is made to set SO_KEEPALIVE on a connection that has already failed.",
	'10053' => "Software caused connection abort.\nAn established connection was aborted by the software in your host computer, possibly due to a data transmission time-out or protocol error.",
	'10054' => "Connection reset by peer.\nAn existing connection was forcibly closed by the remote host. This normally results if the peer application on the remote host is suddenly stopped, the host is rebooted, the host or remote network interface is disabled, or the remote host uses a hard close (see setsockopt for more information on the SO_LINGER option on the remote socket). This error may also result if a connection was broken due to keep-alive activity detecting a failure while one or more operations are in progress. Operations that were in progress fail with WSAENETRESET. Subsequent operations fail with WSAECONNRESET.",
	'10055' => "No buffer space available.\nAn operation on a socket could not be performed because the system lacked sufficient buffer space or because a queue was full.",
	'10056' => "Socket is already connected.\nA connect request was made on an already-connected socket. Some implementations also return this error if sendto is called on a connected SOCK_DGRAM socket (for SOCK_STREAM sockets, the to parameter in sendto is ignored) although other implementations treat this as a legal occurrence.",
	'10057' => "Socket is not connected.\nA request to send or receive data was disallowed because the socket is not connected and (when sending on a datagram socket using sendto) no address was supplied. Any other type of operation might also return this error—for example, setsockopt setting SO_KEEPALIVE if the connection has been reset.",
	'10058' => "Cannot send after socket shutdown.\nA request to send or receive data was disallowed because the socket had already been shut down in that direction with a previous shutdown call. By calling shutdown a partial close of a socket is requested, which is a signal that sending or receiving, or both have been discontinued.",
	'10059' => "Too many references.\nToo many references to some kernel object.",
	'10060' => "Connection timed out.\nA connection attempt failed because the connected party did not properly respond after a period of time, or the established connection failed because the connected host has failed to respond.",
	'10061' => "Connection refused.\nNo connection could be made because the target computer actively refused it. This usually results from trying to connect to a service that is inactive on the foreign host—that is, one with no server application running.",
	'10062' => "Cannot translate name.\nCannot translate a name.",
	'10063' => "Name too long.\nA name component or a name was too long.",
	'10064' => "Host is down.\nA socket operation failed because the destination host is down. A socket operation encountered a dead host. Networking activity on the local host has not been initiated. These conditions are more likely to be indicated by the error WSAETIMEDOUT.",
	'10065' => "No route to host.\nA socket operation was attempted to an unreachable host. See WSAENETUNREACH.",
	'10066' => "Directory not empty.\nCannot remove a directory that is not empty.",
	'10067' => "Too many processes.\nA Windows Sockets implementation may have a limit on the number of applications that can use it simultaneously. WSAStartup may fail with this error if the limit has been reached.",
	'10068' => "User quota exceeded.\nRan out of user quota.",
	'10069' => "Disk quota exceeded.\nRan out of disk quota.",
	'10070' => "Stale file handle reference.\nThe file handle reference is no longer available.",
	'10071' => "Item is remote.\nThe item is not available locally.",
	'10091' => "Network subsystem is unavailable.\nThis error is returned by WSAStartup if the Windows Sockets implementation cannot function at this time because the underlying system it uses to provide network services is currently unavailable. Users should check:\n\tThat the appropriate Windows Sockets DLL file is in the current path.\n\tThat they are not trying to use more than one Windows Sockets implementation simultaneously. If there is more than one Winsock DLL on your system, be sure the first one in the path is appropriate for the network subsystem currently loaded.\n\tThe Windows Sockets implementation documentation to be sure all necessary components are currently installed and configured correctly.",
	'10092' => "Winsock.dll version out of range.\nThe current Windows Sockets implementation does not support the Windows Sockets specification version requested by the application. Check that no old Windows Sockets DLL files are being accessed.",
	'10093' => "Successful WSAStartup not yet performed.\nEither the application has not called WSAStartup or WSAStartup failed. The application may be accessing a socket that the current active task does not own (that is, trying to share a socket between tasks), or WSACleanup has been called too many times.",
	'10101' => "Graceful shutdown in progress.\nReturned by WSARecv and WSARecvFrom to indicate that the remote party has initiated a graceful shutdown sequence.",
	'10102' => "No more results.\nNo more results can be returned by the WSALookupServiceNext function.",
	'10103' => "Call has been canceled.\nA call to the WSALookupServiceEnd function was made while this call was still processing. The call has been canceled.",
	'10104' => "Procedure call table is invalid.\nThe service provider procedure call table is invalid. A service provider returned a bogus procedure table to Ws2_32.dll. This is usually caused by one or more of the function pointers being NULL.",
	'10105' => "Service provider is invalid.\nThe requested service provider is invalid. This error is returned by the WSCGetProviderInfo and WSCGetProviderInfo32 functions if the protocol entry specified could not be found. This error is also returned if the service provider returned a version number other than 2.0.",
	'10106' => "Service provider failed to initialize.\nThe requested service provider could not be loaded or initialized. This error is returned if either a service provider's DLL could not be loaded (LoadLibrary failed) or the provider's WSPStartup or NSPStartup function failed.",
	'10107' => "System call failure.\n
A system call that should never fail has failed. This is a generic error code, returned under various conditions.\n\nReturned when a system call that should never fail does fail. For example, if a call to WaitForMultipleEvents fails or one of the registry functions fails trying to manipulate the protocol/namespace catalogs.\n\nReturned when a provider does not return SUCCESS and does not provide an extended error code. Can indicate a service provider implementation error.",
	'10108' => "Service not found.\nNo such service is known. The service cannot be found in the specified name space.",
	'10109' => "Class type not found.\The specified class was not found.\n",
	'10110' => "No more results.\nNo more results can be returned by the WSALookupServiceNext function.",
	'10111' => "Call was canceled.\n
 call to the WSALookupServiceEnd function was made while this call was still processing. The call has been canceled.",
	'10112' => "Database query was refused.\nA database query failed because it was actively refused.",
	'11001' => "Host not found.\nNo such host is known. The name is not an official host name or alias, or it cannot be found in the database(s) being queried. This error may also be returned for protocol and service queries, and means that the specified name could not be found in the relevant database.",
	'11002' => "Nonauthoritative host not found.\nThis is usually a temporary error during host name resolution and means that the local server did not receive a response from an authoritative server. A retry at some time later may be successful.",
	'11003' => "This is a nonrecoverable error.\nThis indicates that some sort of nonrecoverable error occurred during a database lookup. This may be because the database files (for example, BSD-compatible HOSTS, SERVICES, or PROTOCOLS files) could not be found, or a DNS request was returned by the server with a severe error.",
	'11004' => "Valid name, no data record of requested type.\nThe requested name is valid and was found in the database, but it does not have the correct associated data being resolved for. The usual example for this is a host name-to-address translation attempt (using gethostbyname or WSAAsyncGetHostByName) which uses the DNS (Domain Name Server). An MX record is returned but no A record—indicating the host itself exists, but is not directly reachable.",
	'11005' => "QoS receivers.\nAt least one QoS reserve has arrived.",
	'11006' => "QoS senders.\nAt least one QoS send path has arrived.",
	'11007' => "No QoS senders.\nThere are no QoS senders.",
	'11008' => "No QoS senders.\nThere are no QoS senders.",
	'11009' => "QoS request confirmed.\nThe QoS reserve request has been confirmed.",
	'11010' => "QoS admission error.\nA QoS error occurred due to lack of resources.",
	'11011' => "QoS policy failure.\nThe QoS request was rejected because the policy system couldn't allocate the requested resource within the existing policy.",
	'11012' => "QoS bad style.\nAn unknown or conflicting QoS style was encountered.",
	'11013' => "QoS bad object.\nA problem was encountered with some part of the filterspec or the provider-specific buffer in general.",
	'11014' => "QoS traffic control error.\nAn error with the underlying traffic control (TC) API as the generic QoS request was converted for local enforcement by the TC API. This could be due to an out of memory error or to an internal QoS provider error.",
	'11015' => "QoS generic error.\nA general QoS error.",
	'11016' => "QoS service type error.\nAn invalid or unrecognized service type was found in the QoS flowspec.",
	'11017' => "QoS flowspec error.\nAn invalid or inconsistent flowspec was found in the QOS structure.",
	'11018' => "Invalid QoS provider buffer.\nAn invalid QoS provider-specific buffer.",
	'11019' => "Invalid QoS filter style.\nAn invalid QoS filter style was used.",
	'11020' => "Invalid QoS filter type.\nAn invalid QoS filter type was used.",
	'11021' => "Incorrect QoS filter count.\nAn incorrect number of QoS FILTERSPECs were specified in the FLOWDESCRIPTOR.",
	'11022' => "Invalid QoS object length.\nAn object with an invalid ObjectLength field was specified in the QoS provider-specific buffer.",
	'11023' => "Incorrect QoS flow count.\nAn incorrect number of flow descriptors was specified in the QoS structure.",
	'11024' => "Unrecognized QoS object.\nAn unrecognized object was found in the QoS provider-specific buffer.",
	'11025' => "Invalid QoS policy object.\nAn invalid policy object was found in the QoS provider-specific buffer.",
	'11026' => "Invalid QoS flow descriptor.\nAn invalid QoS flow descriptor was found in the flow descriptor list.",
	'11027' => "Invalid QoS provider-specific flowspec.\nAn invalid or inconsistent flowspec was found in the QoS provider-specific buffer.",
	'11028' => "Invalid QoS provider-specific filterspec.\nAn invalid FILTERSPEC was found in the QoS provider-specific buffer.",
	'11029' => "Invalid QoS shape discard mode object.\nAn invalid shape discard mode object was found in the QoS provider-specific buffer.",
	'11030' => "Invalid QoS shaping rate object.\nAn invalid shaping rate object was found in the QoS provider-specific buffer.",
	'11031' => "Reserved policy QoS element type.\nA reserved policy element was found in the QoS provider-specific buffer.",
    ];

}

?>