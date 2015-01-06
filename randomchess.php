<?php

/**
 * Random chess move generator
 * Author: Dennis Vink (sinned@protonmail.ch, https://www.satanclaus.com)
 * Note: Coded on a drunk saturday night for no particular purpose.
 */

$moveNum = 50; // Set movenum to how many moves you want to generate

$ranks = Array('1' => 'a', '2' => 'b', '3' => 'c', '4' => 'd', '5' => 'e', '6' => 'f', '7' => 'g', 8 => 'h');
$promotion = Array('n','b','r','q');

function getBoard()
{
	return(Array(
		81 => 'R', 82 => 'N', 83 => 'B', 84 => 'Q', 85 => 'K', 86 => 'B', 87 => 'N', 88 => 'R',
		71 => 'P', 72 => 'P', 73 => 'P', 74 => 'P', 75 => 'P', 76 => 'P', 77 => 'P', 78 => 'P',
		61 => ' ', 62 => ' ', 63 => ' ', 64 => ' ', 65 => ' ', 66 => ' ', 67 => ' ', 68 => ' ',
		51 => ' ', 52 => ' ', 53 => ' ', 54 => ' ', 55 => ' ', 56 => ' ', 57 => ' ', 58 => ' ',
		41 => ' ', 42 => ' ', 43 => ' ', 44 => ' ', 45 => ' ', 46 => ' ', 47 => ' ', 48 => ' ',
		31 => ' ', 32 => ' ', 33 => ' ', 34 => ' ', 35 => ' ', 36 => ' ', 37 => ' ', 38 => ' ',
		21 => 'p', 22 => 'p', 23 => 'p', 24 => 'p', 25 => 'p', 26 => 'p', 27 => 'p', 28 => 'p',
		11 => 'r', 12 => 'n', 13 => 'b', 14 => 'q', 15 => 'k', 16 => 'b', 17 => 'n', 18 => 'r'
	));
}

function isChecked($board, $player, $move = NULL)
{
	global $cannotCastle;
	global $movedRookA;
	global $movedRookB;

	if($move)
	{
		$from = key($move);
		$to = $move[$from];

		// Is it a castling move ?
		if($cannotCastle[$player] == false && strtolower($board[$from]) == 'k' && abs($to-$from) == 2)
		{
			if($from > $to) {
				// Long castle
				$board[$to] = $board[$from];
				$board[$from] = ' ';
				$board[($to+1)] = $board[($from-4)];
				$board[($from-4)] = ' ';
			} else {
				// Short castle
				$board[$to] = $board[$from];
				$board[$from] = ' ';
				$board[($to-1)] = $board[($from+3)];
				$board[($from+3)] = ' ';
			}
		}
		else {
			$board[$to] = $board[$from];
			$board[$from] = ' ';
		}
	}
	$myKing = ($player == 1) ? 'k' : 'K';
	$tmpboard = $board;
	foreach($board as $sqm => $piece)
	{
		$t_piece = strtolower($piece);
		if($board[$sqm] != ' ') {
			// Check if it's our own piece
			$isOurOwn = ($player == 1) ? ((strtolower($board[$sqm]) == $board[$sqm]) ? true : false) : ((strtoupper($board[$sqm]) == $board[$sqm]) ? true : false);
			if(!$isOurOwn)
			{
				$file = ($square%10);
				$rank = ($square - ($square % 10))/10;
				$curPos = $sqm;
				if($t_piece == 'p') {
					if($player == 1) {
						$tmpboard[$sqm-9] = '#';
						$tmpboard[$sqm-11] = '#';
					}
					else {
						$tmpboard[$sqm+9] = '#';
						$tmpboard[$sqm+11] = '#';
					}
				}
				if($t_piece == 'b' || $t_piece == 'q') {
					$movements = Array(11,-11,9,-9);
					foreach($movements as $movement) {
						$curPos = $sqm;
						$outofbounds = false;
						while($outofbounds == false)
						{
							$curPos += $movement;
							$t_file = ($curPos%10);
							$t_rank = ($curPos - ($curPos % 10))/10;
							if($t_rank > 8 || $t_file > 8 || $t_rank < 1 || $t_file < 1) {
								$outofbounds = true;
							} else {
								if($board[$curPos] == ' ') {
									$tmpboard[$curPos] = '#';
								} else {
									if($board[$curPos] == $myKing) {
										$tmpboard[$curPos] = '#';
									}
									$outofbounds = true; // Blocking piece
								}
							}
						}
					}
				}
				if($t_piece == 'r' || $t_piece == 'q') {
					$movements = Array(10,-10,-1,1);
					foreach($movements as $movement) {
						$curPos = $sqm;
						$outofbounds = false;
						while($outofbounds == false)
						{
							$curPos += $movement;
							$t_file = ($curPos%10);
							$t_rank = ($curPos - ($curPos % 10))/10;
							if($t_rank > 8 || $t_file > 8 || $t_rank < 1 || $t_file < 1) {
								$outofbounds = true;
							} else {
								if($board[$curPos] == ' ') {
									$tmpboard[$curPos] = '#';
								} else {
									if($board[$curPos] == $myKing) {
										$tmpboard[$curPos] = '#';
									}
									$outofbounds = true; // Blocking piece
								}
							}
						}
					}
				}
				if($t_piece == 'n') {
					$tmpboard[($sqm+21)] = '#';
					$tmpboard[($sqm-21)] = '#';
					$tmpboard[($sqm+19)] = '#';
					$tmpboard[($sqm-19)] = '#';
					$tmpboard[($sqm-12)] = '#';
					$tmpboard[($sqm+12)] = '#';
					$tmpboard[($sqm-8)] = '#';
					$tmpboard[($sqm+8)] = '#';
				}
				if($t_piece == 'k') {
					$tmpboard[($sqm+1)] = '#';
					$tmpboard[($sqm-1)] = '#';
					$tmpboard[($sqm-10)] = '#';
					$tmpboard[($sqm+10)] = '#';
					$tmpboard[($sqm-9)] = '#';
					$tmpboard[($sqm-11)] = '#';
					$tmpboard[($sqm+9)] = '#';
					$tmpboard[($sqm+11)] = '#';
				}
			}
		}
	}
	$foundKing = false;
	foreach($tmpboard as $square => $piece) {
		if($piece == $myKing) {
			$foundKing = true;
		}
	}
	return(($foundKing) ? false : true);
}

function printBoard($board)
{
	$i=0;
	foreach($board as $square => $piece)
	{
		if(!$i) {
			echo '---------------------------------' . PHP_EOL . '| ';
		}
		++$i;
		echo $piece . ' | ';
		echo (!($i%8) ? PHP_EOL . '---------------------------------' . PHP_EOL . ($i < 64 ? '| ' : '') : '');
	}
}

function getMoveList($board, $player = 1)
{
	global $cannotCastle;
	global $movedRookA;
	global $movedRookB;
	$pieces = Array();
	$pieces2 = Array();
	$potential_moves = Array();

	foreach($board as $square => $piece)
	{
		if($player == 1 && strtolower($piece) == $piece && $piece != ' ') {
			$pieces[$square] = $piece;
		}
		else if($player == 1 && strtolower($piece) != $piece && $piece != ' ') {
			$pieces2[$square] = $piece;
		}
		else if($player == 2 && strtoupper($piece) == $piece && $piece != ' ') {
			$pieces[$square] = $piece;
		}
		else if($player == 2 && strtoupper($piece) != $piece && $piece != ' ') {
			$pieces2[$square] = $piece;
		}
	}

	foreach ($pieces as $square => $piece)
	{
		$file = ($square%10);
		$rank = ($square - ($square % 10))/10;
		$t_piece = strtolower($piece);
		if($t_piece == 'p') {
				if($player == 1) {
					// Moving forward
					if($board[($square+10)] == ' ' && $rank < 8) {
						$potential_moves[] = Array($square => ($square+10));
					}
					if ($board[($square+20)] == ' ' && $board[($square+10)] == ' ' && $rank == 2) {
						$potential_moves[] = Array($square => ($square+20));
					}
					// Capturing
					if($pieces2[($square+11)] && $file < 8) {
						$potential_moves[] = Array($square => ($square+11));
					}
					if($pieces2[($square+9)] && $file > 1) {
						$potential_moves[] = Array($square => ($square+9));
					}
				}
				else if ($player == 2) {
					// Moving back Player 2 moves: Ph6 to: 7
					if($board[($square-10)] == ' ' && $rank > 1) {
						$potential_moves[] = Array($square => ($square-10));
					}
					if ($board[($square-20)] == ' ' && $board[($square-10)] == ' ' && $rank == 7) {
						$potential_moves[] = Array($square => ($square-20));
					}
					// Capturing
					if($pieces2[($square-11)] && $file > 1) {
						$potential_moves[] = Array($square => ($square-11));
					}
					if($pieces2[($square-9)] && $file < 8) {
						$potential_moves[] = Array($square => ($square-9));
					}
				}
		}
		else if ($t_piece == 'n') {
				// Two forward, 1 left
				if($file > 1 && $rank < 7 && !$pieces[($square+19)])
				{
					$potential_moves[] = Array($square => ($square+19));
				}
				// Two forward, 1 right
				if($file < 8 && $rank < 7 && !$pieces[($square+21)])
				{
					$potential_moves[] = Array($square => ($square+21));
				}
				// Two right, 1 forward
				if($file < 7 && $rank < 8 && !$pieces[($square+12)]) {
					$potential_moves[] = Array($square => ($square+12));
				}
				// Two right, 1 back
				if($file < 7 && $rank > 1 && !$pieces[($square-8)]) {
					$potential_moves[] = Array($square => ($square-8));
				}
				// Two down, 1 right
				if($rank > 2 && $file < 8 && !$pieces[($square-19)]) {
					$potential_moves[] = Array($square => ($square-19));
				}
				// Two down, 1 left
				if($rank > 2 && $file > 1 && !$pieces[($square-21)]) {
					$potential_moves[] = Array($square => ($square-21));
				}
				// Two left, 1 back
				if($file > 2 && $rank > 1 && !$pieces[($square-12)]) {
					$potential_moves[] = Array($square => ($square-12));
				}
				// Two left, 1 forward
				if($file > 2 && $rank < 8 && !$pieces[($square+8)]) {
					$potential_moves[] = Array($square => ($square+8));
				}
		}
		else if ($t_piece == 'b') {
				// Diagonal forward right
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm += 11;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank > 8 || $t_file > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Diagonal back right
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm -= 9;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank < 1 || $t_file > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Diagonal back left
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm -= 11;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank < 1 || $t_file < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Diagonal forward left
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm += 9;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank > 8 || $t_file < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
		}
		else if ($t_piece == 'q') {
				// Diagonal forward right
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm += 11;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank > 8 || $t_file > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Diagonal back right
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm -= 9;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank < 1 || $t_file > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Diagonal back left
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm -= 11;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank < 1 || $t_file < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Diagonal forward left
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm += 9;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank > 8 || $t_file < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Horizontal left
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm--;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_file < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Horizontal right
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					++$sqm;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_file > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Vertical forward
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm += 10;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Vertical back
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm -= 10;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
		}
		else if ($t_piece == 'r') {
				// Horizontal left
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm--;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_file < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Horizontal right
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					++$sqm;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_file > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Vertical forward
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm += 10;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank > 8 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				// Vertical back
				$sqm = $square;
				$hitobstacle = false;
				while($hitobstacle == false)
				{
					$sqm -= 10;
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($t_rank < 1 || $pieces[$sqm]) {
						$hitobstacle = true;
					}
					else
					{
						if($pieces2[($sqm)]) {
							$potential_moves[] = Array($square => $sqm);
							$hitobstacle = true;
						}
						else {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
		}
		else if ($t_piece == 'k') {
				$movements = Array($square-1,$square+1,$square+10,$square-10,$square-9,$square-11,$square+9,$square+11);
				foreach($movements as $sqm) {
					$t_file = ($sqm%10);
					$t_rank = ($sqm - ($sqm % 10))/10;
					if($board[$sqm] == ' ' || !$pieces[$sqm]) {
						if($t_rank >= 1 && $t_rank <= 8 && $t_file >= 1 && $t_file <= 8) {
							$potential_moves[] = Array($square => $sqm);
						}
					}
				}
				if($cannotCastle[$player] == false && !isChecked($board, $player))
				{
					// Castle long
					$myRook = ($player == 1) ? 'r' : 'R';
					if(!$movedRookA[$player] && $board[($square-1)] == ' ' && $board[($square-2)] == ' ' && $board[($square-3)] == ' ' && $board[($square-4)] == $myRook) {
						$potential_moves[] = Array($square => ($square-2));
					}
					// Castle short
					if(!$movedRookB[$player] && $board[($square+1)] == ' ' && $board[($square+2)] == ' ' && $board[($square+3)] == $myRook) {
						$potential_moves[] = Array($square => ($square+2));
					}
				}
		}
	}
	$movenumber = 0;
	foreach($potential_moves as $testMove) {
		if(isChecked($board, $player, $testMove))
		{
			unset($potential_moves[$movenumber]);
		}
		++$movenumber;
	}
	return($potential_moves);
}

$i=0;
$board = getBoard();
$player = 1;
$cannotCastle = Array(1 => false, 2 => false);
$movedRookA = Array(1 => false, 2 => false);
$movedRookB = Array(1 => false, 2 => false);
$movedKing  = Array(1 => false, 2 => false);
$ranoutofammo = false;
$haveResult = false;
$firstmove = '';
while($i < $moveNum && !$ranoutofammo) {
	++$i;
	$moves = getMoveList($board, $player);
	shuffle($moves);
	$tomove = array_pop($moves);
	$ranoutofammo = false;
	if(!@key($tomove)) {
		$haveResult = true;
		$ranoutofammo = true;
		$staleMate = (isChecked($board, $player) == true) ? false : true;
		if($staleMate) {
			echo "Player $player bas been stalemated. 0.5-0.5\n";
			$score[$firstmove]+=0.5;
		} else {
			echo "Player $player bas been mated. " . ($player == 1 ? '0-1' : '1-0') . "\n";
			if($player == 1) {
				$score[$firstmove]--;
			}
			else {
				$score[$firstmove]+=1;
			}
		}
	} else {
		$sqm = key($tomove);
		$sqm2 = $tomove[$sqm];
		$t_file = ($sqm%10);
		$t_rank = ($sqm - ($sqm % 10))/10;
		$t_file2 = ($sqm2 % 10);
		$t_rank2 = ($sqm2 - ($sqm2 % 10))/10;
		if(strtolower($board[$sqm2]) != 'k') {
			// Castle
			if(strtolower($board[$sqm]) == 'k' && abs($sqm-$sqm2) == 2)
			{
				$cannotCastle[$player] = true;
				if($sqm > $sqm2) {
					// Long castle
					$board[$sqm2] = $board[$sqm];
					$board[$sqm] = ' ';
					$board[($sqm2+1)] = $board[($sqm-4)];
					$board[($sqm-4)] = ' ';
					if(!$firstmove)
						$firstmove = 'O-O-O';
					echo 'Player '.$player.' moves: O-O-O' . PHP_EOL;
				} else {
					// Short castle
					$board[$sqm2] = $board[$sqm];
					$board[$sqm] = ' ';
					$board[($sqm2-1)] = $board[($sqm+3)];
					$board[($sqm+3)] = ' ';
					if(!$firstmove)
						$firstmove = 'O-O';
					echo 'Player '.$player.' moves: O-O' . PHP_EOL;
				}
			}
			else {
					// Detect rook and king moves
					if(strtolower($board[$sqm]) == 'k') {
						$movedKing[$player] = true;
						$cannotCastle[$player] = true;
					}
					if(strtolower($board[$sqm]) == 'r' && $t_file == 1)
					{
						$movedRookA[$player] = true;
					}
					if(strtolower($board[$sqm]) == 'r' && $t_file == 8)
					{
						$movedRookB[$player] = true;
					}
					if($movedRookB[$player] && $movedRookA[$player] == true) {
						$cannotCastle[$player] = true;
					}
				if(strtolower($board[$sqm]) == 'p' && ($t_rank2 == 1 || $t_rank2 == 8)) {
					// Pawn promotion
					shuffle($promotion);
					$promote_to = ($player == 1) ? array_pop($promotion) : strtoupper(array_pop($promotion));
					$board[$sqm2] = $promote_to;
					$board[$sqm] = ' ';
					if(!$firstmove)
						$firstmove = $board[$sqm] . $ranks[$t_file] . $t_rank . '-' . $ranks[$t_file2] . $t_rank2 . '=' . strtoupper($promote_to); 
					echo 'Player '.$player.' moves: ' . $board[$sqm] . $ranks[$t_file] . $t_rank . ' to: ' . $ranks[$t_file2] . $t_rank2 . ' and promotes to ' . strtoupper($promote_to) . PHP_EOL;
				}
				else {
					if(!$firstmove)
						$firstmove = $board[$sqm] . $ranks[$t_file] . $t_rank . '-' . $ranks[$t_file2] . $t_rank2;
					echo 'Player '.$player.' moves: ' . $board[$sqm] . $ranks[$t_file] . $t_rank . ' to: ' . $ranks[$t_file2] . $t_rank2 . PHP_EOL;
					$board[$sqm2] = $board[$sqm];
					$board[$sqm] = ' ';
				}
			}
			$player = ($player == 1) ? 2 : 1;
		}
	}
}

printBoard($board);

?>

