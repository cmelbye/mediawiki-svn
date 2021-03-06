package net.psammead.mwapi.ui;

import net.psammead.mwapi.MediaWikiException;

/** this Exception is thrown, when the connection to a server returns unexpected values */ 
public final class UnexpectedAnswerException extends MediaWikiException {
	/** Constructs a new exception with the specified detail message. */
	public UnexpectedAnswerException(String message) {
		super(message);
	}
	
	/** Constructs a new exception with the specified detail message and cause. */
	public UnexpectedAnswerException(String message, Throwable cause) {
		super(message, cause);
	}

//	/** Constructs a new exception with the specified cause and a detail 
//		message of (cause==null ? null : cause.toString()) (which 
//		typically contains the class and detail message of cause). */
// 	public UnexpectedAnswerException(Throwable cause) {
//		super(cause);	
//	} 
}
