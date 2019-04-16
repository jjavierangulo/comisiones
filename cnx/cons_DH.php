<?php

include_once("cnx.php");

	function TraerConsulta($FechaInicio,$FechaFin, $Cliente, $iniciales){

		$ArregloConsAux = array();	

		$cn = conexion::getInstance();
		$cn->AbrirConexion();

		$FechaInicioD = explode("-",$FechaInicio);
		$FechaFinD = explode("-",$FechaFin);

		$FechaInicioFinal = $FechaInicioD[0] . $FechaInicioD[1] . $FechaInicioD[2];
		$FechaFinFinal = $FechaFinD[0] . $FechaFinD[1] . $FechaFinD[2];

		$ClienteAux = ($Cliente == "HOTELERÍA") ? "HOTELERIA" : $Cliente;
		
		

		/* En Descuento se agrego una validacion de 5centavos (.05) que es lo valido en diferencias de centavos al comparar  (precio unitario * cantidad) = (copiar subtotal) */
		/* En IVA verifica si tiene importe de IVA si es asi el comisionable es el total - iva, de caso contrario el comisionable es el total  */
		$msQuery = "SELECT /* ANTICIPO */ 'A' as TIPO, PAGO.[DocNum] as 'RBO',replace(convert(NVARCHAR, T0.[DocDate], 106), ' ', '/') as 'FRECIBO',PAGO.U_AGENTE 'AGTE', 
				FACTURAANTICIPO.FACT, PAGO.[U_Pedido] as 'PEDIDO',
				case 
				WHEN SUMAARTICULOS.DESCTO is null then (PAGO.CardName  + ' -ERROR DE CAPTURA 1')
				WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then (PAGO.CardName  + ' -ERROR DE CAPTURA 2' + convert(NVARCHAR, SUMAARTICULOS.DESCTO, 106))
				WHEN SUMAARTICULOS.DESCTO is not null then PAGO.CardName 
				END as 'NOMBRE',
				0 AS 'COBRANZA',  
				ROUND(PAGO.ANTICIPO,2) ANTICIPO, 
				ROUND(SUMAARTICULOS.INSTALACION,2) INSTALACION,
				ROUND(SUMAARTICULOS.VIATICOS,2) VIATICOS, 
				IPCLIENTE.TipoCliente 'IPCLIENTE',
				ROUND(SUMAARTICULOS.DESCTO,2) as 'DESCTO',
				ROUND((PAGO.ANTICIPO / (1-SUMAARTICULOS.DESCTO)),2) AS 'VENTA',
				ROUND((PAGO.ANTICIPO / (1-SUMAARTICULOS.DESCTO ) ) * SUMAARTICULOS.DESCTO,2 ) AS 'DESCTO2',
				ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS),2) AS 'COMISIONABLE',
				ROUND(COMISIONVENDEDOR.CONT,2) CONT,
				ROUND(COMISIONVENDEDOR.SEG,2) SEG,
				ROUND(COMISIONVENDEDOR.CIERRE,2) CIERRE,
				ROUND(COMISIONVENDEDOR.ENT,2) ENT,
				ROUND(COMISIONVENDEDOR.VIST,2) VIST,
				CASE 
				WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then 0
				ELSE ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CONTACTO as decimal(10,3))/100) *(COMISIONVENDEDOR.CONT/100) + 
				(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.SEGUIMIENTO as decimal(10,3))/100 ) *(COMISIONVENDEDOR.SEG/100)+
				(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CIERRE as decimal(10,3))/100) * (COMISIONVENDEDOR.CIERRE/100) +
				(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.ENTREGA as decimal(10,3))/100) * (COMISIONVENDEDOR.ENT/100) + 
				(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.VISITA as decimal(10,3))/100) * (COMISIONVENDEDOR.VIST/100),2)
				END AS 'TOTAL',
				case 
				WHEN COMISIONVENDEDOR.CONT > 0 THEN PAGO.U_ETAPA1
				WHEN COMISIONVENDEDOR.SEG > 0 THEN PAGO.U_ETAPA2
				WHEN COMISIONVENDEDOR.CIERRE > 0 THEN PAGO.U_ETAPA3
				WHEN COMISIONVENDEDOR.ENT  > 0 THEN PAGO.U_ETAPA4
				WHEN COMISIONVENDEDOR.VIST > 0 THEN PAGO.U_ETAPA5
				END AS 'VENDEDORCOMISION'
				FROM  [ORCT]  T0 				
				cross apply
				(select T11.DocEntry,T11.[U_Pedido],T11.InvType, T4.CardName,T4.DocEntry DocEntryVenta,T4.DocRate,T4.CardCode, T4.U_AGENTE, 
					T4.U_ETAPA1, T4.U_ETAPA2, T4.U_ETAPA3, T4.U_ETAPA4, T4.U_ETAPA5, T4.[DocCur], isnull(T4.DiscPrcnt,0) as DiscPrcnt,T11.DocNum,
					CASE 
					      WHEN T0.DocCurr='USD'  THEN  T11.[AppliedFC]*T0.DocRate
					      WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]
					 END  as  ANTICIPO , 
					 CASE 
					      WHEN T0.DocCurr='USD'  THEN  ((T11.[AppliedFC]*T0.DocRate)/
					      		CASE 
					      			WHEN T11.[vatApplied] > 0 THEN 1.16
					      			WHEN T11.[vatApplied] = 0 THEN 1
					      		END)
					      WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]/
					      		CASE 
					      			WHEN T11.[vatApplied] > 0 THEN 1.16
					      			WHEN T11.[vatApplied] = 0 THEN 1
					      		END
					 END   as  COBRANZASINIVA 
					 from RCT2 T11 INNER JOIN ORDR T4 ON T4.DocNum = T11.[U_Pedido] where T11.DocNum = T0.DocEntry) PAGO
					CROSS APPLY
					(SELECT T3.DocNum FACT, 
					CASE 
					      WHEN T3.DocCur='USD'  THEN  T3.[DocTotalFC]*T0.DocRate
					      WHEN T3.DocCur='MXP' THEN  T3.[DocTotal]
					 END  as  ANTICIPO,
					CASE 
					      WHEN T3.DocCur='USD'  THEN  (T3.[DocTotalFC]*T0.DocRate)/1.16
					      WHEN T3.DocCur='MXP' THEN  T3.[DocTotal]/1.16
					 END  as   COBRANZASINIVA,
					 U_ETAPA1, U_ETAPA2, U_ETAPA3, U_ETAPA4, U_ETAPA5 from ODPI T3 WHERE T3.DocEntry=PAGO.DocEntry) FACTURAANTICIPO
					CROSS APPLY
					(SELECT O.[GroupName] AS TipoCliente From OCRD T3 inner join OCRG O on (O.GroupCode = T3.GroupCode) where T3.CardCode=PAGO.CardCode) IPCLIENTE 
					cross apply
					(select 	
						CASE 
							WHEN (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100) > -.05 
							     and  (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100) < 0 
							THEN 0
							ELSE (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100)
						END AS  DESCTO,
						 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
					 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=114),0)) /  (sum(T9.[U_TOT_LINEA]) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  INSTALACION ,
						 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
					 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=133),0)) / (sum(T9.[U_TOT_LINEA]) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  VIATICOS 
					from RDR1 T9 where T9.DocEntry=PAGO.DocEntryVenta) SUMAARTICULOS 
					cross apply
					(select 
					(CASE WHEN PAGO.U_ETAPA1=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CONT,
					(CASE WHEN PAGO.U_ETAPA2=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS SEG,
					(CASE WHEN PAGO.U_ETAPA3=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CIERRE,
					(CASE WHEN PAGO.U_ETAPA4=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS ENT,
					(CASE WHEN PAGO.U_ETAPA5=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS VIST
					from [@COMISIONES] COMISIONBASE WHERE COMISIONBASE.U_CLIENTE=IPCLIENTE.TipoCliente AND COMISIONBASE.U_VENDEDOR in ($iniciales))  COMISIONVENDEDOR
					cross apply
					(select U_Contacto CONTACTO, U_Seguimiento  SEGUIMIENTO ,U_Cierre  CIERRE,U_Entrega  ENTREGA,U_Visita  VISITA from [@TIPO_CTE] where U_Codigo=IPCLIENTE.TipoCliente) COMISIONFINAL
					WHERE 
					T0.[DocDate] >= '$FechaInicioFinal' AND  T0.[DocDate] <= '$FechaFinFinal'
					
					and  PAGO.[InvType] =203 and T0.[Canceled]='N'  AND T0.TrsfrAcct>'11102000' AND T0.TrsfrAcct<'11200000' 
	                and (COMISIONVENDEDOR.CONT > 0 or
					COMISIONVENDEDOR.SEG > 0 or
					COMISIONVENDEDOR.CIERRE > 0 or
					COMISIONVENDEDOR.ENT  > 0 or
					COMISIONVENDEDOR.VIST > 0 )  and IPCLIENTE.TipoCliente COLLATE SQL_Latin1_General_Cp1_CI_AI like '%$ClienteAux%'
				union /* COBRANZA */
					SELECT 'B' as TIPO, T0.[DocNum] AS 'RBO', replace(convert(NVARCHAR, T0.[DocDate], 106), ' ', '/') AS 'FRECIBO', DTLS.U_AGENTE AS 'AGTE', 
					DTLS.[DocNum] AS 'FACT', PAGO.[U_Pedido]AS 'PEDIDO', 
					case 
					WHEN SUMAARTICULOS.DESCTO is null then (PAGO.CardName  + ' -ERROR DE CAPTURA 1')
					WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then (PAGO.CardName  + ' -ERROR DE CAPTURA 2')
					WHEN SUMAARTICULOS.DESCTO is not null then PAGO.CardName 
					END as 'NOMBRE',
					ROUND(PAGO.COBRANZA,2) COBRANZA,
					0 as 'ANTICIPO',  
					ROUND(SUMAARTICULOS.INSTALACION,2) INSTALACION,
					ROUND(SUMAARTICULOS.VIATICOS,2) VIATICOS,
					IPCLIENTE.TipoCliente as 'IPCLIENTE',
					ROUND(SUMAARTICULOS.DESCTO,2) as 'DESCTO',
					ROUND((PAGO.COBRANZA / (1-SUMAARTICULOS.DESCTO) ),2) AS 'VENTA',
					ROUND((PAGO.COBRANZA / (1-SUMAARTICULOS.DESCTO ) ) * SUMAARTICULOS.DESCTO,2 ) AS 'DESCTO2',
					ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS),2) AS 'COMISIONABLE',
					ROUND(COMISIONVENDEDOR.CONT,2) CONT,
					ROUND(COMISIONVENDEDOR.SEG,2) SEG,
					ROUND(COMISIONVENDEDOR.CIERRE,2) CIERRE,
					ROUND(COMISIONVENDEDOR.ENT,2) ENT,
					ROUND(COMISIONVENDEDOR.VIST,2) VIST,
					CASE 
					WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then 0
					ELSE ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CONTACTO as decimal(10,3))/100)*(COMISIONVENDEDOR.CONT/100) + 
						(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)*(cast(COMISIONFINAL.SEGUIMIENTO as decimal(10,3))/100)*(COMISIONVENDEDOR.SEG/100)+
						(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)*(cast(COMISIONFINAL.CIERRE as decimal(10,3))/100)*(COMISIONVENDEDOR.CIERRE/100) +
						(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)*(cast(COMISIONFINAL.ENTREGA as decimal(10,3))/100)*(COMISIONVENDEDOR.ENT/100) + 
						(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)*(cast(COMISIONFINAL.VISITA as decimal(10,3))/100)*(COMISIONVENDEDOR.VIST/100),2) 
					END AS 'TOTAL',
				case 
	                                                          WHEN COMISIONVENDEDOR.CONT > 0 THEN PAGO.U_ETAPA1
					  WHEN COMISIONVENDEDOR.SEG > 0 THEN PAGO.U_ETAPA2
					 WHEN COMISIONVENDEDOR.CIERRE > 0 THEN PAGO.U_ETAPA3
					 WHEN COMISIONVENDEDOR.ENT  > 0 THEN PAGO.U_ETAPA4
					 WHEN COMISIONVENDEDOR.VIST > 0 THEN PAGO.U_ETAPA5
				END AS 'VENDEDORCOMISION'
					FROM [ORCT]  T0 
					cross apply
					(select T11.DocEntry,T11.[U_Pedido],T11.InvType, T4.CardName,T4.DocEntry DocEntryVenta,T4.DocRate,T4.CardCode,
						T4.U_ETAPA1, T4.U_ETAPA2, T4.U_ETAPA3, T4.U_ETAPA4, T4.U_ETAPA5,
						CASE 
					      WHEN T0.DocCurr='USD'  THEN  T11.[AppliedFC]*T0.DocRate
					      WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]
					 END  as  COBRANZA , 
					 CASE 
					      /*WHEN T0.DocCurr='USD'  THEN  ((T11.[AppliedFC]*T0.DocRate)/1.16)
					      WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]/1.16*/
					       	WHEN T0.DocCurr='USD'  THEN  ((T11.[AppliedFC]*T0.DocRate)/
					      		CASE 
					      			WHEN T11.[vatApplied] > 0 THEN 1.16
					      			WHEN T11.[vatApplied] = 0 THEN 1
					      		END)
					      	WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]/
					      		CASE 
					      			WHEN T11.[vatApplied] > 0 THEN 1.16
					      			WHEN T11.[vatApplied] = 0 THEN 1
					      	END
					 END   as  COBRANZASINIVA 

					 from RCT2 T11 INNER JOIN ORDR T4 ON T4.DocNum = T11.[U_Pedido]
					 where T0.DocEntry = T11.DocNum) PAGO
					CROSS APPLY
					(select  T2.U_AGENTE,T2.[DocNum],T2.CardName,T2.[DocCur],T2.[FolioPref],T2.DocEntry,
					  CASE 
					      WHEN T2.DocCur='USD'  THEN  T2.[DocTotalFC]*T0.DocRate
					      WHEN T2.DocCur='MXP' THEN  T2.[DocTotal]
					 END  as  COBRANZA , 
					 CASE 
					      WHEN T2.DocCur='USD'  THEN  ((T2.[DocTotalFC]*T0.DocRate)/1.16)
					      WHEN T2.DocCur='MXP' THEN  T2.[DocTotal]/1.16
					 END   as  COBRANZASINIVA , 
					T2.DiscPrcnt,
					T2.U_ETAPA1,T2.U_ETAPA2,T2.U_ETAPA3,T2.U_ETAPA4,T2.U_ETAPA5 from  OINV T2 
					where  T2.DocEntry = PAGO.DocEntry) DTLS
					cross apply
					(SELECT O.[GroupName] AS TipoCliente From OCRG O inner join OCRD T3 on (O.GroupCode = T3.GroupCode) where T3.CardCode=PAGO.CardCode)  IPCLIENTE
					cross apply
					(select 	
						CASE 
							WHEN ROUND((((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (DTLS.DiscPrcnt/100),2) > -.05 
								 and 
								 ROUND((((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (DTLS.DiscPrcnt/100),2) < 0 
							THEN 0
							ELSE ROUND((((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (DTLS.DiscPrcnt/100),2) 
						END AS DESCTO,
						 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN DTLS.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
					 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=114),0)) /  (sum(T9.[U_TOT_LINEA]) * (CASE WHEN DTLS.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  INSTALACION ,
						 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN DTLS.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
					 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=133),0)) / (sum(T9.[U_TOT_LINEA]) * (CASE WHEN DTLS.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  VIATICOS 
					from RDR1 T9 where T9.DocEntry=PAGO.DocEntryVenta) SUMAARTICULOS 
					cross apply
					(select 
					(CASE WHEN PAGO.U_ETAPA1=COMISIONBASE.U_VENDEDOR THEN (select ([@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO)))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CONT,
					(CASE WHEN PAGO.U_ETAPA2=COMISIONBASE.U_VENDEDOR THEN (select ([@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO)))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS SEG,
					(CASE WHEN PAGO.U_ETAPA3=COMISIONBASE.U_VENDEDOR THEN (select ([@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO)))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CIERRE,
					(CASE WHEN PAGO.U_ETAPA4=COMISIONBASE.U_VENDEDOR THEN (select ([@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO)))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS ENT,
					(CASE WHEN PAGO.U_ETAPA5=COMISIONBASE.U_VENDEDOR THEN (select ([@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO)))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS VIST
					from [@COMISIONES] COMISIONBASE WHERE COMISIONBASE.U_CLIENTE=IPCLIENTE.TipoCliente AND COMISIONBASE.U_VENDEDOR in ($iniciales))  COMISIONVENDEDOR
					cross apply
					(select  U_Contacto CONTACTO, U_Seguimiento  SEGUIMIENTO ,U_Cierre  CIERRE, U_Entrega ENTREGA,U_Visita VISITA from [@TIPO_CTE] where U_Codigo=IPCLIENTE.TipoCliente) COMISIONFINAL
					WHERE PAGO.[U_Pedido] IS NOT NULL 
					and PAGO.InvType ='13' and
					T0.[Canceled]='N' AND T0.TrsfrAcct>'11102000' AND T0.TrsfrAcct<'11200000'
					and  T0.[DocDate] >= '$FechaInicioFinal' AND  T0.[DocDate] <= '$FechaFinFinal'
		 			and (COMISIONVENDEDOR.CONT > 0 or
						COMISIONVENDEDOR.SEG > 0 or
						COMISIONVENDEDOR.CIERRE > 0 or
						COMISIONVENDEDOR.ENT  > 0 or
						COMISIONVENDEDOR.VIST > 0)  and IPCLIENTE.TipoCliente COLLATE SQL_Latin1_General_Cp1_CI_AI like '%$ClienteAux%'
				union /* ANTICIPO SIN CLIENTE EN FACTURA */
					SELECT 'A' as TIPO, PAGO.[DocNum] as 'RBO',replace(convert(NVARCHAR, T0.[DocDate], 106), ' ', '/') as 'FRECIBO',PAGO.U_AGENTE 'AGTE', 
						'' FACT, PAGO.[U_Pedido] as 'PEDIDO',
						case 
						WHEN SUMAARTICULOS.DESCTO is null then (PAGO.CardName  + ' -ERROR DE CAPTURA 1')
						WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then (PAGO.CardName  + ' -ERROR DE CAPTURA 2')
						WHEN SUMAARTICULOS.DESCTO is not null then PAGO.CardName 
						END as 'NOMBRE',
						0 AS 'COBRANZA',  
						ROUND(PAGO.ANTICIPO,2) ANTICIPO, 
						ROUND(SUMAARTICULOS.INSTALACION,2) INSTALACION,
						ROUND(SUMAARTICULOS.VIATICOS,2) VIATICOS, 
						IPCLIENTE.TipoCliente 'IPCLIENTE',
						ROUND(SUMAARTICULOS.DESCTO,2) as 'DESCTO',
						ROUND((PAGO.ANTICIPO / (1-SUMAARTICULOS.DESCTO)),2) AS 'VENTA',
						ROUND((PAGO.ANTICIPO / (1-SUMAARTICULOS.DESCTO )) * SUMAARTICULOS.DESCTO,2 ) AS 'DESCTO2',
						ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS),2) AS 'COMISIONABLE',
						ROUND(COMISIONVENDEDOR.CONT,2) CONT,
						ROUND(COMISIONVENDEDOR.SEG,2) SEG,
						ROUND(COMISIONVENDEDOR.CIERRE,2) CIERRE,
						ROUND(COMISIONVENDEDOR.ENT,2) ENT,
						ROUND(COMISIONVENDEDOR.VIST,2) VIST,
						CASE 
							WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then 0
							ELSE ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CONTACTO as decimal(10,3))/100) *(COMISIONVENDEDOR.CONT/100) + 
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.SEGUIMIENTO as decimal(10,3))/100 ) *(COMISIONVENDEDOR.SEG/100)+
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CIERRE as decimal(10,3))/100) * (COMISIONVENDEDOR.CIERRE/100) +
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.ENTREGA as decimal(10,3))/100) * (COMISIONVENDEDOR.ENT/100) + 
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.VISITA as decimal(10,3))/100) * (COMISIONVENDEDOR.VIST/100),2) 
						END AS 'TOTAL',
					case 
					WHEN COMISIONVENDEDOR.CONT > 0 THEN PAGO.U_ETAPA1
					WHEN COMISIONVENDEDOR.SEG > 0 THEN PAGO.U_ETAPA2
					WHEN COMISIONVENDEDOR.CIERRE > 0 THEN PAGO.U_ETAPA3
					WHEN COMISIONVENDEDOR.ENT  > 0 THEN PAGO.U_ETAPA4
					WHEN COMISIONVENDEDOR.VIST > 0 THEN PAGO.U_ETAPA5
					END AS 'VENDEDORCOMISION'
						FROM  [ORCT]  T0 				
						cross apply
						(select /*T11.DocEntry,*/T11.[U_Pedido]/*,T11.InvType,*/, T4.CardName,T4.DocEntry DocEntryVenta,T4.DocRate,T4.CardCode, T4.U_AGENTE, 
						T4.U_ETAPA1, T4.U_ETAPA2, T4.U_ETAPA3, T4.U_ETAPA4, T4.U_ETAPA5, T4.[DocCur], T4.DiscPrcnt,T11.DocNum,
						CASE 
						      WHEN T0.DocCurr='USD'  THEN  T11.[AppliedFC]*T0.DocRate
						      WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]
						 END  as  ANTICIPO , 
						 CASE 
						      WHEN T0.DocCurr='USD'  THEN  ((T11.[AppliedFC]*T0.DocRate)/1.16)
						      WHEN T0.DocCurr='MXP' THEN  T11.[SumApplied]/1.16
						 END   as  COBRANZASINIVA 
						 from RCT4 T11 INNER JOIN ORDR T4 ON T4.DocNum = T11.[U_Pedido] where T11.DocNum = T0.DocEntry) PAGO
						CROSS APPLY
						(SELECT O.[GroupName] AS TipoCliente From OCRD T3 inner join OCRG O on (O.GroupCode = T3.GroupCode) where T3.CardCode=PAGO.CardCode) IPCLIENTE 
						cross apply
						(select 	
							CASE 
								WHEN (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100) > -.05
										and (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100) < 0 
								THEN 0
								ELSE (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100)
							END AS DESCTO,
							 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
						 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=114),0)) /  (sum(T9.[U_TOT_LINEA]) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  INSTALACION ,
							 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
						 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=133),0)) / (sum(T9.[U_TOT_LINEA]) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  VIATICOS 
						from RDR1 T9 where T9.DocEntry=PAGO.DocEntryVenta) SUMAARTICULOS 
						cross apply
						(select 
						(CASE WHEN PAGO.U_ETAPA1=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CONT,
						(CASE WHEN PAGO.U_ETAPA2=COMISIONBASE.U_VENDEDOR THEN (select  [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS SEG,
						(CASE WHEN PAGO.U_ETAPA3=COMISIONBASE.U_VENDEDOR THEN (select  [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CIERRE,
						(CASE WHEN PAGO.U_ETAPA4=COMISIONBASE.U_VENDEDOR THEN (select  [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS ENT,
						(CASE WHEN PAGO.U_ETAPA5=COMISIONBASE.U_VENDEDOR THEN (select  [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS VIST
						from [@COMISIONES] COMISIONBASE WHERE COMISIONBASE.U_CLIENTE=IPCLIENTE.TipoCliente AND COMISIONBASE.U_VENDEDOR in ($iniciales))  COMISIONVENDEDOR
						cross apply
						(select U_Contacto CONTACTO, U_Seguimiento  SEGUIMIENTO ,U_Cierre  CIERRE,U_Entrega  ENTREGA,U_Visita  VISITA from [@TIPO_CTE] where U_Codigo=IPCLIENTE.TipoCliente) COMISIONFINAL
						WHERE 
						T0.[DocDate] >= '$FechaInicioFinal' AND  T0.[DocDate] <= '$FechaFinFinal'
						and   T0.[Canceled]='N'  AND T0.TrsfrAcct>'11102000' AND T0.TrsfrAcct<'11200000' 
						 and (COMISIONVENDEDOR.CONT > 0 or
						COMISIONVENDEDOR.SEG > 0 or
						COMISIONVENDEDOR.CIERRE > 0 or
						COMISIONVENDEDOR.ENT  > 0 or
						COMISIONVENDEDOR.VIST > 0)  and IPCLIENTE.TipoCliente COLLATE SQL_Latin1_General_Cp1_CI_AI like '%$ClienteAux%'
					union /* NOTA DE CREDITO */
						SELECT 'C' as TIPO, T0.[DocNum] as 'RBO',replace(convert(NVARCHAR, T0.[DocDate], 106), ' ', '/') as 'FRECIBO',PAGO.U_AGENTE 'AGTE', 
						/*FACTURAANTICIPO.FACT*/ PAGO.[DocNum] FACT, PAGO.[U_OCPCT] as 'PEDIDO',
						case 
						WHEN SUMAARTICULOS.DESCTO is null then (PAGO.CardName  + ' -ERROR DE CAPTURA 1')
						WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then (PAGO.CardName  + ' -ERROR DE CAPTURA 2')
						WHEN SUMAARTICULOS.DESCTO is not null then PAGO.CardName 
						END as 'NOMBRE',
						0 AS 'COBRANZA',  
						ROUND(PAGO.ANTICIPO,2) ANTICIPO, 
						ROUND(SUMAARTICULOS.INSTALACION,2) INSTALACION,
						ROUND(SUMAARTICULOS.VIATICOS,2) VIATICOS, 
						IPCLIENTE.TipoCliente 'IPCLIENTE',
						ROUND(SUMAARTICULOS.DESCTO,2) as 'DESCTO',
						ROUND((PAGO.ANTICIPO / (1-SUMAARTICULOS.DESCTO) ),2) AS 'VENTA',
						ROUND((PAGO.ANTICIPO / (1-SUMAARTICULOS.DESCTO ) ) * SUMAARTICULOS.DESCTO,2 ) AS 'DESCTO2',
						ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS),2) AS 'COMISIONABLE',
						ROUND(COMISIONVENDEDOR.CONT,2) CONT,
						ROUND(COMISIONVENDEDOR.SEG,2) SEG,
						ROUND(COMISIONVENDEDOR.CIERRE,2) CIERRE,
						ROUND(COMISIONVENDEDOR.ENT,2) ENT,
						ROUND(COMISIONVENDEDOR.VIST,2) VIST,
						CASE 
					WHEN SUMAARTICULOS.DESCTO is not null and SUMAARTICULOS.DESCTO < 0 then 0
							ELSE ROUND((PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CONTACTO as decimal(10,3))/100) *(COMISIONVENDEDOR.CONT/100) + 
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.SEGUIMIENTO as decimal(10,3))/100 ) *(COMISIONVENDEDOR.SEG/100)+
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.CIERRE as decimal(10,3))/100) * (COMISIONVENDEDOR.CIERRE/100) +
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.ENTREGA as decimal(10,3))/100) * (COMISIONVENDEDOR.ENT/100) + 
							(PAGO.COBRANZASINIVA - SUMAARTICULOS.INSTALACION - SUMAARTICULOS.VIATICOS)* (cast(COMISIONFINAL.VISITA as decimal(10,3))/100) * (COMISIONVENDEDOR.VIST/100),2) 
						END AS 'TOTAL',
						case 
						WHEN COMISIONVENDEDOR.CONT > 0 THEN PAGO.U_ETAPA1
						WHEN COMISIONVENDEDOR.SEG > 0 THEN PAGO.U_ETAPA2
						WHEN COMISIONVENDEDOR.CIERRE > 0 THEN PAGO.U_ETAPA3
						WHEN COMISIONVENDEDOR.ENT  > 0 THEN PAGO.U_ETAPA4
						WHEN COMISIONVENDEDOR.VIST > 0 THEN PAGO.U_ETAPA5
						END AS 'VENDEDORCOMISION'
						FROM  [ORCT]  T0 				
						cross apply
						(select /*T11.DocEntry,*/T11.U_OCPCT/*,T11.InvType,*/, T4.CardName,T4.DocEntry DocEntryVenta,T4.DocRate,T4.CardCode, T4.U_AGENTE, 
						T4.U_ETAPA1, T4.U_ETAPA2, T4.U_ETAPA3, T4.U_ETAPA4, T4.U_ETAPA5, T4.[DocCur], T4.DiscPrcnt,T11.DocNum,
						CASE 
						      WHEN T0.DocCurr='USD'  THEN  T11.[DocTotalFC]*T0.DocRate
						      WHEN T0.DocCurr='MXP' THEN  T11.[DocTotal]
						 END  as  ANTICIPO , 
						 CASE 
						      WHEN T0.DocCurr='USD'  THEN  ((T11.[DocTotalFC]*T0.DocRate)/
						      	CASE 
						      		WHEN T11.[VatSum] > 0 THEN 1.16
						      		WHEN T11.[VatSum] = 0 THEN 1
						      	END)
						      WHEN T0.DocCurr='MXP' THEN  T11.[DocTotal]/
						      	CASE
						      		WHEN T11.[VatSum] > 0 THEN 1.16
						      		WHEN T11.[VatSum] = 0 THEN 1
						      	END 
						 END   as  COBRANZASINIVA 
						 from ORIN T11 INNER JOIN ORDR T4 ON T4.DocNum = T11.U_OCPCT where T11.ReceiptNum = T0.DocEntry) PAGO
						CROSS APPLY
						(SELECT O.[GroupName] AS TipoCliente From OCRD T3 inner join OCRG O on (O.GroupCode = T3.GroupCode) where T3.CardCode=PAGO.CardCode) IPCLIENTE 
						cross apply
						(select 	
							CASE 
								WHEN (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100) > -.05
									and (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100) < 0 
								THEN 0
								ELSE (((sum(T9.[Quantity] * T9.PriceBefDi)) - (sum(T9.[U_TOT_LINEA]))) / (sum(T9.[Quantity] * T9.PriceBefDi))) +   (PAGO.DiscPrcnt/100)
							END AS DESCTO,
							 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
						 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=114),0)) /  (sum(T9.[U_TOT_LINEA]) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  INSTALACION ,
							 ((PAGO.COBRANZASINIVA) * isnull((select (SUM(T10.PRICE * T10.Quantity)) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END)  from RDR1 T10 inner join OITM O2 ON O2.ItemCode = T10.ItemCode 
						 where T10.DocEntry=PAGO.DocEntryVenta  and O2.ItmsGrpCod=133),0)) / (sum(T9.[U_TOT_LINEA]) * (CASE WHEN PAGO.[DocCur] = 'MXP' THEN 1 ELSE PAGO.DocRate END))  VIATICOS 
						from RDR1 T9 where T9.DocEntry=PAGO.DocEntryVenta) SUMAARTICULOS 
						cross apply
						(select 
						(CASE WHEN PAGO.U_ETAPA1=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CONT,
						(CASE WHEN PAGO.U_ETAPA2=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS SEG,
						(CASE WHEN PAGO.U_ETAPA3=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS CIERRE,
						(CASE WHEN PAGO.U_ETAPA4=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS ENT,
						(CASE WHEN PAGO.U_ETAPA5=COMISIONBASE.U_VENDEDOR THEN (select [@COMISIONES].U_COMISION-([@COMISIONES].U_COMISION*((SUMAARTICULOS.DESCTO))) FROM [@COMISIONES] WHERE [@COMISIONES].U_CLIENTE=IPCLIENTE.TipoCliente and [@COMISIONES].U_VENDEDOR=COMISIONBASE.U_VENDEDOR) ELSE 0 END) AS VIST
						from [@COMISIONES] COMISIONBASE WHERE COMISIONBASE.U_CLIENTE=IPCLIENTE.TipoCliente AND COMISIONBASE.U_VENDEDOR in ($iniciales))  COMISIONVENDEDOR
						cross apply
						(select U_Contacto CONTACTO, U_Seguimiento  SEGUIMIENTO ,U_Cierre  CIERRE,U_Entrega  ENTREGA,U_Visita  VISITA from [@TIPO_CTE] where U_Codigo=IPCLIENTE.TipoCliente) COMISIONFINAL
						WHERE 
						T0.[DocDate] >= '$FechaInicioFinal' AND  T0.[DocDate] <= '$FechaFinFinal'
						and  T0.[Canceled]='N'  AND T0.TrsfrAcct>'11102000' AND T0.TrsfrAcct<'11200000' 
	                    and (COMISIONVENDEDOR.CONT > 0 or
						COMISIONVENDEDOR.SEG > 0 or
						COMISIONVENDEDOR.CIERRE > 0 or
						COMISIONVENDEDOR.ENT  > 0 or
						COMISIONVENDEDOR.VIST > 0)  and IPCLIENTE.TipoCliente COLLATE SQL_Latin1_General_Cp1_CI_AI like '%$ClienteAux%'";
		

		$msresults= mssql_query($msQuery);
		while ($row = mssql_fetch_array($msresults)) {  

			array_push($ArregloConsAux,array('TIPO'=>$row['TIPO'],
										  'RBO'=>$row['RBO'],
										  'FRECIBO'=> $row['FRECIBO'],
										  'AGTE'=> $row['AGTE'],
										  'FACT'=> $row['FACT'],
										  'PEDIDO'=> $row['PEDIDO'],
										  'NOMBRE'=> mb_convert_encoding($row['NOMBRE'], 'UTF-8', 'CP850'),
										  'COBRANZA'=> $row['COBRANZA'],
										  'ANTICIPO'=> $row['ANTICIPO'],
										  'INSTALACION'=> $row['INSTALACION'],
										  'VIATICOS'=> $row['VIATICOS'],
										  'IPCLIENTE'=> mb_convert_encoding($row['IPCLIENTE'], 'UTF-8', 'CP850') ,
										  'DESCTO'=> $row['DESCTO'],
										  'VENTA'=> $row['VENTA'],
										  'DESCTO2'=> $row['DESCTO2'],
										  'COMISIONABLE'=> $row['COMISIONABLE'],
										  'CONT'=> $row['CONT'],
										  'SEG'=> $row['SEG'],
										  'CIERRE'=> $row['CIERRE'],
										  'ENT'=> $row['ENT'],
										  'VIST'=> $row['VIST'],
										  'TOTAL'=> $row['TOTAL'],
										  'VENDEDORCOMISION' => $row['VENDEDORCOMISION']));
		}

			

		$cn->CerrarConexion();

	
		$Filas = count($ArregloConsAux);
		$Comisiones = array();

	    $Recibo = "";
	    $Pedido = "";
	    $Factura = "";

	  	for ($i=0; $i<$Filas; $i++) {

	    	if ($Recibo ==  $ArregloConsAux[$i]['RBO'] &&  $Pedido == $ArregloConsAux[$i]['PEDIDO'] && $Factura == $ArregloConsAux[$i]['FACT']) {
	          	$Cobranza = 0;
	          	$Anticipo = 0;
	          	$Instalacion = 0;
	          	$Viaticos = 0;
	          	$Venta = 0;
	          	$Descto2 = 0;
	    	} else {
	          	$Cobranza = $ArregloConsAux[$i]['COBRANZA'];
	          	$Anticipo = $ArregloConsAux[$i]['ANTICIPO'];
	          	$Instalacion = $ArregloConsAux[$i]['INSTALACION'];
	          	$Viaticos = $ArregloConsAux[$i]['VIATICOS'];
	          	$Venta = $ArregloConsAux[$i]['VENTA'];
	          	$Descto2 = $ArregloConsAux[$i]['DESCTO2'];
	    	}


	       array_push($Comisiones,array('TIPO'=>$ArregloConsAux[$i]['TIPO'],
	                  'RBO'=>$ArregloConsAux[$i]['RBO'],
	                  'FRECIBO'=> $ArregloConsAux[$i]['FRECIBO'],
	                  'AGTE'=> $ArregloConsAux[$i]['AGTE'],
	                  'FACT'=> $ArregloConsAux[$i]['FACT'],
	                  'PEDIDO'=> $ArregloConsAux[$i]['PEDIDO'],
	                  'NOMBRE'=> $ArregloConsAux[$i]['NOMBRE'],
	                  'COBRANZA'=>  $Cobranza,
	                  'ANTICIPO'=> $Anticipo,
	                  'INSTALACION'=> $Instalacion,
	                  'VIATICOS'=> $Viaticos,
	                  'IPCLIENTE'=> $ArregloConsAux[$i]['IPCLIENTE'] ,
	                  'DESCTO'=>$ArregloConsAux[$i]['DESCTO'],
	                  'VENTA'=> $Venta,
	                  'DESCTO2'=> $Descto2,
	                  'COMISIONABLE'=>$ArregloConsAux[$i]['COMISIONABLE'],
	                  'CONT'=> $ArregloConsAux[$i]['CONT'],
	                  'SEG'=> $ArregloConsAux[$i]['SEG'],
	                  'CIERRE'=> $ArregloConsAux[$i]['CIERRE'],
	                  'ENT'=> $ArregloConsAux[$i]['ENT'],
	                  'VIST'=> $ArregloConsAux[$i]['VIST'],
	                  'TOTAL'=> $ArregloConsAux[$i]['TOTAL'],
	                  'VENDEDORCOMISION' => $ArregloConsAux[$i]['VENDEDORCOMISION']));



	        $Recibo =  $ArregloConsAux[$i]['RBO'];
	        $Pedido = $ArregloConsAux[$i]['PEDIDO'];
	        $Factura = $ArregloConsAux[$i]['FACT'];

	  	} 


	  $ArregloCons = msort($Comisiones, array('VENDEDORCOMISION', 'RBO'));

	  return $ArregloCons;

	}



	function TraerPorcentajeVendedores($Iniciales){

		 //$msQuery = "SELECT U_CLIENTE, U_COMISION FROM @COMISION_VENDEDORES WHERE U_ESTATUS = 1 AND U_INICIALES='$Iniciales';";
		//$msQuery= "SELECT T0.[CODE],T0.[U_INICIALES] 'INICIALES', T0.[U_CLIENTE] 'CLIENTE', T0.[U_COMISION] 'COMISION' FROM [dbo].[@COMISION_VENDEDORES]  T0 WHERE T0.[U_INICIALES] ='$Iniciales'";
		$msQuery = "SELECT  T0.[U_VENDEDOR] 'INICIALES', T0.[U_CLIENTE] 'CLIENTE', T0.[U_COMISION] 'COMISION' FROM [dbo].[@COMISIONES]  T0 WHERE T0.[U_VENDEDOR] ='$Iniciales'";

		$Comisiones = array();	

		$cn = conexion::getInstance();
		$cn->AbrirConexion();

		$msresults= mssql_query($msQuery);
		while ($row = mssql_fetch_array($msresults)) { 

			array_push($Comisiones,array('CLIENTE'=>mb_convert_encoding($row['CLIENTE'], 'UTF-8', 'CP850'),
										 'COMISION'=> $row['COMISION'],
										 'VENDEDOR'=> $row['INICIALES']
										 ));
		}

		//iconv('ISO-8859-1','UTF-8',$row['CLIENTE'])

		$cn->CerrarConexion();

		return $Comisiones;
		
	}



	function TraerTipodeCliente($Iniciales){

		//$msQuery = "SELECT T0.[GroupName], T0.[GroupType] FROM OCRG T0 where GroupType='C'";

		$msQuery = "SELECT T1.[U_Codigo] TIPO FROM [dbo].[@TIPO_CTE] T1 WHERE T1.[U_Codigo] not in (select T0.[U_CLIENTE]  from [dbo].[@COMISION_VENDEDORES] T0 where T0.[U_INICIALES] ='$Iniciales')";

		$Cliente = array();	

		$cn = conexion::getInstance();
		$cn->AbrirConexion();

		$msresults= mssql_query($msQuery);
		while ($row = mssql_fetch_array($msresults)) { 

			array_push($Cliente,array('CLIENTE'=>mb_convert_encoding($row['TIPO'], 'UTF-8', 'CP850')));
		}

		$cn->CerrarConexion();

		return $Cliente;

	}



    function msort($array, $key, $sort_flags = SORT_REGULAR) {
        if (is_array($array) && count($array) > 0) {
            if (!empty($key)) {
                $mapping = array();
                foreach ($array as $k => $v) {
                    $sort_key = '';
                    if (!is_array($key)) {
                        $sort_key = $v[$key];
                    } else {
                        // @TODO This should be fixed, now it will be sorted as string
                        foreach ($key as $key_key) {
                            $sort_key .= $v[$key_key];
                        }
                        $sort_flags = SORT_STRING;
                    }
                    $mapping[$k] = $sort_key;
                }
                asort($mapping, $sort_flags);
                $sorted = array();
                foreach ($mapping as $k => $v) {
                    $sorted[] = $array[$k];
                }
                return $sorted;
            }
        }
        return $array;
    }




?>