			USE `bpmspace_coms_v1`;

			DELIMITER //
			DROP PROCEDURE IF EXISTS demo_data;

			//

			CREATE PROCEDURE  demo_data()
			begin
			DECLARE x SMALLINT DEFAULT 0;
			  while x < 10000 
			  do
				SET @lastname = generate_lname();
				SET @firstname = generate_fname();

				INSERT INTO .`coms_participant` (`coms_participant_lastname`, `coms_participant_firstname`, `coms_participant_public`, `coms_participant_placeofbirth`, `coms_participant_birthcountry`) VALUES (@lastname, @firstname, '0', str_random('Cccc(4)'), str_random('Cccc(7)'));
				SET @lastid = LAST_INSERT_ID();
				INSERT INTO `coms_participant_identifier` (`coms_participant_id`, `coms_participant_matriculation`, `coms_participant_md5`) VALUES (@lastid, @lastid, md5(concat(@lastid,@firstname,@lastname)));

				set x = x+1;

			  end while;

			END;

			//

			DELIMITER ;
call demo_data();
UPDATE `coms_participant_identifier` set `coms_participant_matriculation` = concat(`coms_participant_id`,SUBSTRING(CONV(SUBSTRING(coms_participant_md5,1,5),16,10),1,3)) where true;
UPDATE `coms_participant_identifier` set `coms_participant_base32` = LPAD(CONV(`coms_participant_matriculation`,10,32),8,'0') where true;



SELECT coms_participant_base32, count(coms_participant_identifier_id) AS cnt FROM bpmspace_coms_v1.coms_participant_identifier GROUP BY coms_participant_base32 Having cnt > 1;